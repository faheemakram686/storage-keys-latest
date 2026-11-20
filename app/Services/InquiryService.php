<?php

namespace App\Services;

use App\Mail\InquiryMail;
use App\Models\Inquiry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

/**
 * Quote / contact inquiries: shared by the website form (InquiryController)
 * and the mobile app API (Api\WebsiteFormsController).
 */
class InquiryService
{
    /** Optional fields appended to the message as "Label: value" lines. */
    public const EXTRA_FIELDS = ['source', 'company', 'size', 'storing', 'duration', 'volume', 'items'];

    /** Storage types offered by the website quote form. */
    public const STORAGE_TYPES = [
        'Personal Storage',
        'Business Storage',
        'Warehouse Storage',
        'Climate Controlled Storage',
        'Moving',
        'Luggage Storage',
        'Car Storage',
    ];

    /**
     * Save the inquiry, email the team and push it to the Google Sheet.
     * Email and sheet failures are logged only and never block the inquiry.
     *
     * @param array $validated name, email, phone, storage_type, message
     */
    public function create(array $validated, Request $request): Inquiry
    {
        $extras = [];
        foreach (self::EXTRA_FIELDS as $key) {
            $val = trim((string) $request->input($key, ''));
            if ($val !== '') {
                $extras[] = ucfirst(str_replace('_', ' ', $key)) . ': ' . $val;
            }
        }

        if ($extras) {
            $validated['message'] = trim(($validated['message'] ?? '') . "\n\n" . implode("\n", $extras));
        }

        $inquiry = DB::transaction(function () use ($validated) {
            return Inquiry::create($validated);
        });

        try {
            $notifyTo = config('mail.inquiry_to');
            if (!empty($notifyTo)) {
                Mail::to($notifyTo)->send(new InquiryMail($inquiry));
            }
        } catch (\Exception $mailEx) {
            Log::error('Inquiry email failed: '.$mailEx->getMessage());
        }

        $this->pushToGoogleSheet($inquiry, $request);

        return $inquiry;
    }

    public function reference(?Inquiry $inquiry): string
    {
        return 'SK-' . str_pad((string) ($inquiry->id ?? 0), 5, '0', STR_PAD_LEFT);
    }

    /**
     * Detect common bot / spam inquiry submissions.
     */
    public function isSpam(Request $request): bool
    {
        // Honeypot: real users never see/fill this field.
        // Do not name it "website" — browsers often autofill that and block real people.
        if (filled($request->input('sk_hp_field'))) {
            return true;
        }

        // Only flag instant bot posts (< 1s). Autofill + quick submit is common for real users.
        $startedAt = (int) $request->input('form_started_at', 0);
        if ($startedAt > 0 && (time() - $startedAt) < 1) {
            return true;
        }

        $name = trim((string) $request->input('name', ''));
        $email = strtolower(trim((string) $request->input('email', '')));
        $phone = preg_replace('/\s+/', '', (string) $request->input('phone', ''));
        $message = trim((string) $request->input('message', ''));

        if ($name !== '' && $this->looksLikeBotName($name)) {
            return true;
        }

        // Random gibberish company names from optional field.
        $company = trim((string) $request->input('company', ''));
        if ($company !== '' && $this->looksLikeBotName($company)) {
            return true;
        }

        // Disposable / known spam-heavy domains.
        $domain = substr(strrchr($email, '@') ?: '', 1);
        $blockedDomains = [
            'mailinator.com', 'guerrillamail.com', 'tempmail.com', '10minutemail.com',
            'yopmail.com', 'trashmail.com', 'sharklasers.com', 'getnada.com',
        ];
        if ($domain && in_array($domain, $blockedDomains, true)) {
            return true;
        }

        // Message packed with URLs is usually spam.
        if ($message !== '' && preg_match_all('/https?:\/\//i', $message) >= 2) {
            return true;
        }

        // Phone with almost no digits.
        $digits = preg_replace('/\D+/', '', $phone);
        if ($digits !== null && strlen($digits) > 0 && strlen($digits) < 7) {
            return true;
        }

        return false;
    }

    public function logSpam(Request $request): void
    {
        Log::info('inquiry.spam_blocked', [
            'ip' => $request->ip(),
            'email' => $request->input('email'),
            'name' => $request->input('name'),
            'ua' => $request->userAgent(),
        ]);
    }

    private function looksLikeBotName(string $value): bool
    {
        $value = trim($value);
        if ($value === '') {
            return false;
        }

        // No spaces, long, almost no vowels → random keyboard spam.
        if (!preg_match('/\s/u', $value)
            && mb_strlen($value) >= 8
            && !preg_match('/[aeiouAEIOU]/u', $value)
        ) {
            return true;
        }

        // High ratio of consonants / mixed case junk like "MzrodLfcocuym".
        $letters = preg_replace('/[^a-zA-Z]/', '', $value);
        if ($letters !== null && strlen($letters) >= 10) {
            $vowels = preg_match_all('/[aeiouAEIOU]/', $letters);
            if ($vowels !== false && $vowels / strlen($letters) < 0.15) {
                return true;
            }
        }

        return false;
    }

    /**
     * Append a lead row to the configured Google Apps Script webhook.
     * Failures are logged only — they must never block the inquiry flow.
     */
    private function pushToGoogleSheet(Inquiry $inquiry, Request $request): void
    {
        $url = config('services.google_sheets.webhook_url');
        if (empty($url)) {
            return;
        }

        try {
            $payload = [
                'timestamp' => now()->toIso8601String(),
                'name' => (string) $inquiry->name,
                'email' => (string) $inquiry->email,
                'phone' => (string) $inquiry->phone,
                'storage_type' => (string) $inquiry->storage_type,
                'message' => (string) ($inquiry->message ?? ''),
                'source' => (string) $request->input('source', 'website'),
                'page' => (string) ($request->input('page') ?: $request->headers->get('referer') ?: $request->fullUrl()),
            ];

            $response = Http::timeout(8)
                ->withOptions(['allow_redirects' => true])
                ->asJson()
                ->post($url, $payload);

            if (!$response->successful()) {
                Log::warning('Google Sheets webhook non-success', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);
            } else {
                Log::info('Google Sheets webhook ok', [
                    'inquiry_id' => $inquiry->id,
                    'body' => $response->body(),
                ]);
            }
        } catch (\Throwable $e) {
            Log::error('Google Sheets webhook failed: '.$e->getMessage());
        }
    }
}
