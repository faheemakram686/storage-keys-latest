<?php

namespace App\Services;

use App\Models\CustomerPaymentMethod;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class NgeniusPaymentService
{
    protected string $baseUrl;
    protected string $outletReference;
    protected NgeniusTokenService $tokenService;

    public function __construct(NgeniusTokenService $tokenService)
    {
        $this->baseUrl = rtrim((string) config('services.ngenius.base_url'), '/');
        $this->outletReference = (string) config('services.ngenius.outlet_reference');
        $this->tokenService = $tokenService;
    }

    /**
     * Create a hosted-page order.
     *
     * @param  mixed  $redirectUrl  Truthy = invoice return path; null/false = checkout return path
     * @param  mixed  $customerRef  Customer model (or object with billing fields)
     * @param  array  $options  merchant_order_reference, cancel_url, include_saved_card
     */
    public function createOrder($amount, $currency = 'AED', $redirectUrl = null, $customerRef = null, array $options = [])
    {
        return $this->doCreateOrder($amount, $currency, $redirectUrl, $customerRef, $options, true);
    }

    protected function doCreateOrder($amount, $currency, $redirectUrl, $customerRef, array $options, bool $allowRetry)
    {
        $token = $this->tokenService->getAccessToken();
        if (!$token) {
            Log::error('Failed to get Ngenius access token');
            return false;
        }

        $billing = $this->resolveBillingDetails($customerRef);
        if ($billing['email'] === '' || $billing['first_name'] === '') {
            Log::error('Ngenius order refused: missing payer email or first name', [
                'has_email' => $billing['email'] !== '',
                'has_first_name' => $billing['first_name'] !== '',
            ]);
            return false;
        }

        if ($billing['last_name'] === '') {
            $billing['last_name'] = '-';
        }

        $resolvedRedirectUrl = $this->resolveRedirectUrl($redirectUrl !== null);
        if (!$resolvedRedirectUrl) {
            Log::error('Ngenius redirect URL could not be resolved', [
                'app_url' => config('app.url'),
                'callback_url' => config('services.ngenius.callback_url'),
            ]);
            return false;
        }

        $endpoint = $this->baseUrl . '/transactions/outlets/' . $this->outletReference . '/orders';

        $merchantAttributes = [
            'maskPaymentInfo' => true,
            'redirectUrl' => $resolvedRedirectUrl,
        ];

        $cancelUrl = trim((string) ($options['cancel_url'] ?? ''));
        if ($cancelUrl !== '' && $this->isUsableRedirectUrl($this->normalizeRedirectUrl($cancelUrl), !app()->environment('production'))) {
            $merchantAttributes['cancelUrl'] = $this->normalizeRedirectUrl($cancelUrl);
        }

        // Unattended MIT / recurring charges: ask the outlet to skip shopper 3DS when allowed.
        if (!empty($options['skip_3ds'])) {
            $merchantAttributes['skip3DS'] = true;
        }

        $payload = [
            'action' => 'SALE',
            'amount' => [
                'currencyCode' => $currency,
                'value' => (int) round(((float) $amount) * 100),
            ],
            'emailAddress' => $billing['email'],
            'paymentAttempts' => 3,
            'merchantAttributes' => $merchantAttributes,
            'billingAddress' => [
                'firstName' => $billing['first_name'],
                'lastName' => $billing['last_name'],
                'address1' => $billing['address'] !== '' ? $billing['address'] : 'N/A',
                'city' => $billing['city'] !== '' ? $billing['city'] : 'Dubai',
                'countryCode' => $billing['country_code'],
            ],
        ];

        $merchantOrderReference = $this->sanitizeMerchantOrderReference($options['merchant_order_reference'] ?? null);
        if ($merchantOrderReference) {
            $payload['merchantOrderReference'] = $merchantOrderReference;
        }

        if (!empty($options['include_saved_card']) && is_object($customerRef) && !empty($customerRef->id)) {
            $saved = CustomerPaymentMethod::activeForCustomer((int) $customerRef->id);
            if ($saved) {
                $payload['savedCard'] = $saved->toNgeniusSavedCard();
            }
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $token,
                'Content-Type' => 'application/vnd.ni-payment.v2+json',
                'Accept' => 'application/vnd.ni-payment.v2+json',
            ])->post($endpoint, $payload);

            if ($response->successful()) {
                $json = $response->json();
                Log::info('Ngenius Order Creation successful', [
                    'status' => $response->status(),
                    'reference' => $json['reference'] ?? null,
                    'redirectUrl' => $resolvedRedirectUrl,
                ]);
                return $json;
            }

            if ($response->status() === 401 && $allowRetry) {
                $this->tokenService->clearTokenCache();
                return $this->doCreateOrder($amount, $currency, $redirectUrl, $customerRef, $options, false);
            }

            Log::error('Ngenius Order Creation Failed', [
                'status' => $response->status(),
                'redirectUrl' => $resolvedRedirectUrl,
                'body' => $response->json() ?? $response->body(),
            ]);

            return false;
        } catch (\Exception $e) {
            Log::error('Ngenius Order Creation Exception', ['error' => $e->getMessage()]);
            return false;
        }
    }

    /**
     * Charge a saved card without the hosted page (unattended recurring).
     *
     * @return array{order: array, payment: array|false}|false
     */
    public function chargeSavedCard($amount, $currency, $customerRef, CustomerPaymentMethod $paymentMethod, array $options = [])
    {
        $order = $this->doCreateOrder($amount, $currency, 1, $customerRef, array_merge($options, [
            'include_saved_card' => false,
        ]), true);

        if (!$order) {
            return false;
        }

        $savedCardHref = $this->extractSavedCardPaymentHref($order);
        if (!$savedCardHref) {
            Log::error('Ngenius saved-card charge: payment:saved-card link missing', [
                'reference' => $order['reference'] ?? null,
            ]);
            return false;
        }

        $payment = $this->submitSavedCard($savedCardHref, $paymentMethod, true);

        return [
            'order' => $order,
            'payment' => $payment,
        ];
    }

    protected function submitSavedCard(string $href, CustomerPaymentMethod $paymentMethod, bool $allowRetry)
    {
        $token = $this->tokenService->getAccessToken();
        if (!$token) {
            return false;
        }

        $body = $paymentMethod->toNgeniusSavedCard();
        $body['recaptureCsc'] = false;

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $token,
                'Content-Type' => 'application/vnd.ni-payment.v2+json',
                'Accept' => 'application/vnd.ni-payment.v2+json',
            ])->put($href, $body);

            if ($response->successful()) {
                $json = $response->json();
                Log::info('Ngenius saved-card charge response', [
                    'state' => $json['state'] ?? null,
                    'orderReference' => $json['orderReference'] ?? null,
                ]);
                return $json;
            }

            if ($response->status() === 401 && $allowRetry) {
                $this->tokenService->clearTokenCache();
                return $this->submitSavedCard($href, $paymentMethod, false);
            }

            Log::error('Ngenius saved-card charge failed', [
                'status' => $response->status(),
            ]);

            return false;
        } catch (\Exception $e) {
            Log::error('Ngenius saved-card charge exception', ['error' => $e->getMessage()]);
            return false;
        }
    }

    protected function resolveRedirectUrl(bool $forInvoice = true): ?string
    {
        $path = $forInvoice ? '/redirect-response' : '/redirectPaymentRef';
        $allowPrivate = !app()->environment('production');
        $candidates = [];

        // Prefer the current request root (includes subdirectory + port), e.g.
        // http://127.0.0.1/storage-keys-latest/public or http://127.0.0.1:8000
        if (!app()->runningInConsole() && request()) {
            $candidates[] = rtrim(request()->root(), '/') . $path;
        }

        try {
            $candidates[] = url($path);
        } catch (\Throwable $e) {
            // url() helper may be unavailable in some contexts.
        }

        try {
            $candidates[] = $forInvoice
                ? route('invoice.redirect-response', [], true)
                : route('handle.redirect', [], true);
        } catch (\Throwable $e) {
            // Named route may be unavailable in some contexts.
        }

        $configured = trim((string) config('services.ngenius.callback_url', ''));
        if ($configured !== '' && stripos($configured, 'yourdomain.com') === false) {
            $base = $this->extractAppBase($configured);
            if ($base) {
                $candidates[] = rtrim($base, '/') . $path;
            }
        }

        $appUrl = rtrim((string) config('app.url'), '/');
        if ($appUrl !== '') {
            $candidates[] = $appUrl . $path;
        }

        foreach (array_unique(array_filter($candidates)) as $url) {
            $normalized = $this->normalizeRedirectUrl($url);
            if ($this->isUsableRedirectUrl($normalized, $allowPrivate)) {
                return $normalized;
            }
        }

        return null;
    }

    /**
     * Site base for building redirect URLs. Keeps subdirectory paths
     * (e.g. /storage-keys-latest/public) and strips known callback endpoints.
     */
    protected function extractAppBase(string $url): ?string
    {
        $parts = parse_url($url);
        if (empty($parts['scheme']) || empty($parts['host'])) {
            return null;
        }

        $base = $parts['scheme'] . '://' . $parts['host']
            . (isset($parts['port']) ? ':' . $parts['port'] : '');

        $path = (string) ($parts['path'] ?? '');
        $path = preg_replace('#/(redirect-response|redirectPaymentRef|payment/callback)/?$#i', '', $path);
        $path = rtrim((string) $path, '/');

        return $base . $path;
    }

    /** @deprecated Use extractAppBase() */
    protected function extractBaseUrl(string $url): ?string
    {
        return $this->extractAppBase($url);
    }

    protected function normalizeRedirectUrl(?string $url): ?string
    {
        if (!$url) {
            return null;
        }

        return preg_replace('#://localhost(?=[:/]|$)#i', '://127.0.0.1', $url);
    }

    protected function isPrivateHost(?string $host): bool
    {
        $host = strtolower((string) $host);

        return $host === ''
            || $host === 'localhost'
            || $host === '127.0.0.1'
            || $host === '::1'
            || str_ends_with($host, '.local')
            || str_ends_with($host, '.test');
    }

    protected function isUsableRedirectUrl(?string $url, bool $allowPrivate = true): bool
    {
        if (!$url || !preg_match('#^https?://#i', $url)) {
            return false;
        }

        if (stripos($url, 'yourdomain.com') !== false) {
            return false;
        }

        $host = strtolower((string) parse_url($url, PHP_URL_HOST));
        if ($host === '' || $host === 'localhost') {
            return false;
        }

        if (!$allowPrivate && $this->isPrivateHost($host)) {
            return false;
        }

        return true;
    }

    protected function sanitizeMerchantOrderReference($value): ?string
    {
        $value = preg_replace('/[^A-Za-z0-9\-]/', '', (string) $value);
        return $value !== '' ? $value : null;
    }

    /**
     * N-Genius requires merchantOrderReference to be unique per order.
     * Reusing the same invoice number on a second Pay Now causes HTTP 422.
     */
    public function uniqueMerchantOrderReference(?string $invoiceNo, ?int $invoiceId = null): string
    {
        $base = $this->sanitizeMerchantOrderReference($invoiceNo)
            ?: ('INV' . ($invoiceId ?: '0'));

        $suffix = strtoupper(substr(str_replace('.', '', uniqid('', true)), -8));

        return $this->sanitizeMerchantOrderReference($base . '-' . $suffix) ?: ($base . $suffix);
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

    public function getOrderStatus($reference, bool $allowRetry = true)
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

            if ($response->status() === 401 && $allowRetry) {
                $this->tokenService->clearTokenCache();
                return $this->getOrderStatus($reference, false);
            }

            Log::error('Ngenius Order Status Failed', [
                'status' => $response->status(),
                'reference' => $reference,
            ]);

            return false;
        } catch (\Exception $e) {
            Log::error('Ngenius Order Status Exception', ['error' => $e->getMessage()]);
            return false;
        }
    }

    /**
     * Latest payment block from an order response (recurring orders grow the array).
     */
    public function extractLatestPayment($orderResponse): ?array
    {
        $payments = $this->extractPayments($orderResponse);
        if ($payments === []) {
            return null;
        }

        return $payments[array_key_last($payments)];
    }

    /**
     * Prefer a successful payment block; fall back to the latest.
     */
    public function extractSuccessfulPayment($orderResponse): ?array
    {
        foreach (array_reverse($this->extractPayments($orderResponse)) as $payment) {
            if ($this->isPaidState($payment['state'] ?? null)) {
                return $payment;
            }
        }

        return $this->extractLatestPayment($orderResponse);
    }

    public function extractPayments($orderResponse): array
    {
        $payments = $orderResponse['_embedded']['payment'] ?? null;
        if (!is_array($payments) || $payments === []) {
            return [];
        }

        return array_values(array_filter($payments, 'is_array'));
    }

    public function extractPaymentState($orderResponse)
    {
        $payment = $this->extractSuccessfulPayment($orderResponse);
        return $payment['state'] ?? null;
    }

    public function extractCapturedAmount($orderResponse)
    {
        $payment = $this->extractSuccessfulPayment($orderResponse);
        return $payment['amount']['value'] ?? null;
    }

    public function extractCurrency($orderResponse): ?string
    {
        $payment = $this->extractSuccessfulPayment($orderResponse);
        $currency = $payment['amount']['currencyCode']
            ?? $orderResponse['amount']['currencyCode']
            ?? null;

        return is_string($currency) ? strtoupper($currency) : null;
    }

    public function extractSavedCard($orderResponse): ?array
    {
        $payment = $this->extractSuccessfulPayment($orderResponse);
        $saved = $payment['savedCard'] ?? null;
        if (!is_array($saved) || empty($saved['cardToken'])) {
            return null;
        }

        return $saved;
    }

    public function extractSavedCardPaymentHref($orderResponse): ?string
    {
        $payment = $this->extractLatestPayment($orderResponse);
        $fromPayment = $payment['_links']['payment:saved-card']['href']
            ?? $payment['_links']['cnp:payment:saved-card']['href']
            ?? null;
        if ($fromPayment) {
            return $fromPayment;
        }

        return $orderResponse['_links']['payment:saved-card']['href']
            ?? $orderResponse['_links']['cnp:payment:saved-card']['href']
            ?? null;
    }

    public function isPaid($orderResponse): bool
    {
        foreach ($this->extractPayments($orderResponse) as $payment) {
            if ($this->isPaidState($payment['state'] ?? null)) {
                return true;
            }
        }

        return false;
    }

    public function isPaidState(?string $state): bool
    {
        return in_array($state, ['PURCHASED', 'CAPTURED'], true);
    }

    public function isPendingState(?string $state): bool
    {
        return in_array($state, ['STARTED', 'AWAIT_3DS', 'PENDING', 'AUTHORISED'], true);
    }

    public function isFailedState(?string $state): bool
    {
        return in_array($state, ['FAILED', 'DECLINED', 'CANCELLED', 'CANCELED', 'EXPIRED'], true);
    }

    public function getPaymentUrl($orderResponse)
    {
        return $orderResponse['_links']['payment']['href'] ?? null;
    }

    public function getOrderReference($orderResponse)
    {
        return $orderResponse['reference'] ?? null;
    }
}
