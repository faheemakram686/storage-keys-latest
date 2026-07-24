<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class NgeniusPaymentService
{
    protected $baseUrl;
    protected $outletReference;
    protected $tokenService;

    public function __construct(NgeniusTokenService $tokenService)
    {
        $this->baseUrl = env('NGENIUS_BASE_URL');
        $this->outletReference = env('NGENIUS_OUTLET_REFERENCE');
        $this->tokenService = $tokenService;
    }

    public function createOrder($amount, $currency = 'AED', $redirectUrl = null, $customerRef = null)
    {
        $token = $this->tokenService->getAccessToken();

        if (!$token) {
            Log::error('Failed to get Ngenius access token');
            return false;
        }

        $billing = $this->resolveBillingDetails($customerRef);
        $resolvedRedirectUrl = $this->resolveRedirectUrl($redirectUrl !== null);

        if (!$resolvedRedirectUrl) {
            Log::error('Ngenius redirect URL could not be resolved', [
                'app_url' => config('app.url'),
                'callback_url' => config('services.ngenius.callback_url'),
            ]);
            return false;
        }

        $endpoint = $this->baseUrl . '/transactions/outlets/' . $this->outletReference . '/orders';

        $payload = [
            'action' => 'SALE',
            'amount' => [
                'currencyCode' => $currency,
                'value' => (int) round(((float) $amount) * 100), // minor units
            ],
            'emailAddress' => $billing['email'],
            'merchantAttributes' => [
                'maskPaymentInfo' => true,
                'paymentAttempts' => '3',
                'redirectUrl' => $resolvedRedirectUrl,
            ],
            'billingAddress' => [
                'firstName' => $billing['first_name'],
                'lastName' => $billing['last_name'],
                'address1' => $billing['address'],
                'city' => $billing['city'],
                'countryCode' => $billing['country_code'],
            ],
        ];

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $token,
                'Content-Type' => 'application/vnd.ni-payment.v2+json',
                'Accept' => 'application/vnd.ni-payment.v2+json',
            ])->post($endpoint, $payload);

            if ($response->successful()) {
                Log::info('Ngenius Order Creation successful', [
                    'status' => $response->status(),
                    'redirectUrl' => $resolvedRedirectUrl,
                    'response' => $response->body(),
                ]);
                return $response->json();
            }

            // If token might be expired, clear cache and retry once
            if ($response->status() === 401) {
                $this->tokenService->clearTokenCache();
                return $this->createOrder($amount, $currency, $redirectUrl, $customerRef);
            }

            Log::error('Ngenius Order Creation Failed', [
                'status' => $response->status(),
                'redirectUrl' => $resolvedRedirectUrl,
                'response' => $response->body(),
            ]);

            return false;
        } catch (\Exception $e) {
            Log::error('Ngenius Order Creation Exception', ['error' => $e->getMessage()]);
            return false;
        }
    }

    /**
     * N-Genius rejects hostname "localhost". Prefer the live request host
     * (e.g. http://127.0.0.1:8000), then a configured public callback URL.
     */
    protected function resolveRedirectUrl(bool $forInvoice = true): ?string
    {
        $path = $forInvoice ? '/redirect-response' : '/redirectPaymentRef';

        $candidates = [];

        $configured = trim((string) config('services.ngenius.callback_url', ''));
        if ($configured !== '') {
            $candidates[] = $configured;
        }

        if (!app()->runningInConsole() && request()) {
            $candidates[] = rtrim(request()->getSchemeAndHttpHost(), '/') . $path;
        }

        try {
            $candidates[] = $forInvoice
                ? route('invoice.redirect-response', [], true)
                : route('handle.redirect', [], true);
        } catch (\Throwable $e) {
            // Named route may be unavailable in some contexts.
        }

        $candidates[] = rtrim((string) config('app.url'), '/') . $path;

        foreach ($candidates as $url) {
            $normalized = $this->normalizeRedirectUrl($url);
            if ($this->isUsableRedirectUrl($normalized)) {
                return $normalized;
            }
        }

        return null;
    }

    protected function normalizeRedirectUrl(?string $url): ?string
    {
        if (!$url) {
            return null;
        }

        // N-Genius rejects the hostname "localhost" but accepts 127.0.0.1.
        return preg_replace('#://localhost(?=[:/]|$)#i', '://127.0.0.1', $url);
    }

    protected function isUsableRedirectUrl(?string $url): bool
    {
        if (!$url || !preg_match('#^https?://#i', $url)) {
            return false;
        }

        if (stripos($url, 'yourdomain.com') !== false) {
            return false;
        }

        $host = strtolower((string) parse_url($url, PHP_URL_HOST));

        return $host !== '' && $host !== 'localhost';
    }

    protected function resolveBillingDetails($customerRef): array
    {
        $contact = null;
        if (is_object($customerRef) && method_exists($customerRef, 'relationLoaded')) {
            $contact = $customerRef->relationLoaded('primaryContact')
                ? $customerRef->primaryContact
                : (method_exists($customerRef, 'primaryContact') ? $customerRef->primaryContact()->first() : null);
        }

        $displayName = '';
        if (is_object($customerRef)) {
            $displayName = trim((string) (
                $customerRef->customer_name
                ?: $customerRef->company_name
                ?: (($contact->first_name ?? '') . ' ' . ($contact->last_name ?? ''))
            ));
        }

        $parts = $displayName !== '' ? explode(' ', $displayName, 2) : [];

        $country = is_object($customerRef) ? strtoupper(trim((string) ($customerRef->country ?? ''))) : '';
        // N-Genius expects an ISO country code; default to AE for this app.
        if ($country === '' || strlen($country) !== 2) {
            $country = 'AE';
        }

        return [
            'email' => (string) (
                (is_object($customerRef) ? ($customerRef->email ?? null) : null)
                ?: ($contact->email ?? null)
                ?: ''
            ),
            'first_name' => (string) ($contact->first_name ?? $parts[0] ?? ''),
            'last_name' => (string) ($contact->last_name ?? $parts[1] ?? ''),
            'address' => (string) (is_object($customerRef) ? ($customerRef->address ?? '') : ''),
            'city' => (string) (is_object($customerRef) ? ($customerRef->city ?? '') : ''),
            'country_code' => $country,
        ];
    }

    /**
     * Fetch the current status of an Ngenius order by its reference.
     * Returns the decoded order array on success, or false on failure.
     */
    public function getOrderStatus($reference)
    {
        $token = $this->tokenService->getAccessToken();

        if (!$token) {
            Log::error('Failed to get Ngenius access token for order status');
            return false;
        }

        $endpoint = $this->baseUrl . '/transactions/outlets/' . $this->outletReference . '/orders/' . $reference;

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $token,
                'Accept' => 'application/vnd.ni-payment.v2+json',
            ])->get($endpoint);

            if ($response->successful()) {
                return $response->json();
            }

            // Token may be expired: clear cache and retry once.
            if ($response->status() === 401) {
                $this->tokenService->clearTokenCache();
                return $this->getOrderStatus($reference);
            }

            Log::error('Ngenius Order Status Failed', [
                'status' => $response->status(),
                'response' => $response->body(),
            ]);

            return false;
        } catch (\Exception $e) {
            Log::error('Ngenius Order Status Exception', ['error' => $e->getMessage()]);
            return false;
        }
    }

    /**
     * Extract the payment state (e.g. PURCHASED, CAPTURED, FAILED) from an order response.
     */
    public function extractPaymentState($orderResponse)
    {
        return $orderResponse['_embedded']['payment'][0]['state'] ?? null;
    }

    /**
     * Extract the captured amount in minor units from an order response.
     */
    public function extractCapturedAmount($orderResponse)
    {
        return $orderResponse['_embedded']['payment'][0]['amount']['value'] ?? null;
    }

    /**
     * Whether the given order response represents a successfully paid order.
     */
    public function isPaid($orderResponse)
    {
        return in_array($this->extractPaymentState($orderResponse), ['PURCHASED', 'CAPTURED'], true);
    }

    public function getPaymentUrl($orderResponse)
    {
        if (isset($orderResponse['_links']['payment']['href'])) {
            return $orderResponse['_links']['payment']['href'];
        }
        return null;
    }

    public function getOrderReference($orderResponse)
    {
        return $orderResponse['reference'] ?? null;
    }
}