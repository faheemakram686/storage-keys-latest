<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Mail\InquiryMail;
use App\Models\Inquiry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class InquiryController extends Controller
{
    public function index()
    {
        return view('backend.inquiry.index');
    }

    public function store(Request $request)
    {
        try {
            // Silent fake-success for bots — never write DB / email / Google Sheet.
            if ($this->isSpamInquiry($request)) {
                Log::info('inquiry.spam_blocked', [
                    'ip' => $request->ip(),
                    'email' => $request->input('email'),
                    'name' => $request->input('name'),
                    'ua' => $request->userAgent(),
                ]);

                return redirect()->route('inquiry.thankyou')->with('inquiry', [
                    'name' => (string) $request->input('name', ''),
                    'email' => (string) $request->input('email', ''),
                    'phone' => (string) $request->input('phone', ''),
                    'storage_type' => (string) $request->input('storage_type', ''),
                    'reference' => 'SK-00000',
                ]);
            }

            if ($this->recaptchaEnabled() && !$this->verifyRecaptcha($request)) {
                return redirect()->back()
                    ->withErrors(['g-recaptcha-response' => 'Please confirm you are not a robot.'])
                    ->withInput();
            }

            $validated = $request->validate([
                'name'   => 'required|string|max:255',
                'email'  => 'required|email',
                'storage_type' => 'required|string',
                'phone'  => 'required|string|max:40',
                'message'=> 'nullable|string',
            ]);

            $extras = [];
            foreach (['source', 'company', 'size', 'storing', 'duration', 'volume', 'items'] as $key) {
                $val = trim((string) $request->input($key, ''));
                if ($val !== '') {
                    $extras[] = ucfirst(str_replace('_', ' ', $key)) . ': ' . $val;
                }
            }

            if ($extras) {
                $validated['message'] = trim(($validated['message'] ?? '') . "\n\n" . implode("\n", $extras));
            }

            DB::beginTransaction();

            $inquiry = Inquiry::create($validated);

            DB::commit();

            try {
                $notifyTo = config('mail.inquiry_to');
                if (!empty($notifyTo)) {
                    Mail::to($notifyTo)->send(new InquiryMail($inquiry));
                }
            } catch (\Exception $mailEx) {
                Log::error('Inquiry email failed: '.$mailEx->getMessage());
            }

            $this->pushInquiryToGoogleSheet($inquiry, $request);

            return redirect()->route('inquiry.thankyou')->with('inquiry', [
                'name' => $inquiry->name,
                'email' => $inquiry->email,
                'phone' => $inquiry->phone,
                'storage_type' => $inquiry->storage_type,
                'reference' => 'SK-' . str_pad((string) $inquiry->id, 5, '0', STR_PAD_LEFT),
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Inquiry submission failed: '.$e->getMessage());

            return redirect()->back()->with('error', 'Something went wrong while submitting your inquiry. Please try again later.');
        }
    }

    private function recaptchaEnabled(): bool
    {
        return filled(config('services.recaptcha.site_key'))
            && filled(config('services.recaptcha.secret_key'));
    }

    private function verifyRecaptcha(Request $request): bool
    {
        $token = (string) $request->input('g-recaptcha-response', '');
        if ($token === '') {
            return false;
        }

        try {
            $response = Http::asForm()
                ->timeout(8)
                ->post('https://www.google.com/recaptcha/api/siteverify', [
                    'secret' => config('services.recaptcha.secret_key'),
                    'response' => $token,
                    'remoteip' => $request->ip(),
                ]);

            $ok = (bool) data_get($response->json(), 'success', false);
            if (!$ok) {
                Log::info('inquiry.recaptcha_failed', [
                    'ip' => $request->ip(),
                    'errors' => data_get($response->json(), 'error-codes'),
                ]);
            }

            return $ok;
        } catch (\Throwable $e) {
            Log::error('inquiry.recaptcha_error: '.$e->getMessage());

            // Fail closed when keys are configured — do not accept unverified posts.
            return false;
        }
    }

    /**
     * Detect common bot / spam inquiry submissions.
     */
    private function isSpamInquiry(Request $request): bool
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
    private function pushInquiryToGoogleSheet(Inquiry $inquiry, Request $request): void
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
                'page' => (string) ($request->headers->get('referer') ?: $request->fullUrl()),
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

    public function getInquires(Request $request)
    {
        try {
            $qry = Inquiry::all();
            return $qry;
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function deleteInquiry(Request $request)
    {
        $qry = Inquiry::find($request->id);
        $qry->delete();
        if ($qry) {
            return response()->json(['success' => 'Record deleted successfully'], 200);
        }

        return response()->json(['error' => 'Record not deleted, Technical Error'], 200);
    }
}
