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

    public function createOrder($amount, $currency = 'AED',$redirectUrl = null)
    {
        $token = $this->tokenService->getAccessToken();


        if (!$token) {
            Log::error('Failed to get Ngenius access token');
            return false;
        }

        $endpoint = $this->baseUrl . '/transactions/outlets/' . $this->outletReference . '/orders';

        $payload = [
            'action' => 'SALE',
            'amount' => [
                'currencyCode' => $currency,
                'value' => $amount * 100, // Convert to minor units
            ],
            'emailAddress' => "test@gmail.com",
            'merchantAttributes' => [
                'maskPaymentInfo' => true,
                'paymentAttempts' => "3",
                'redirectUrl' => $redirectUrl!=null? route('invoice.redirect-response'):route('handle.redirect'),
            ]
        ];

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $token,
                'Content-Type' => 'application/vnd.ni-payment.v2+json',
                'Accept' => 'application/vnd.ni-payment.v2+json',
            ])->post($endpoint, $payload);

            if ($response->successful()) {
                Log::error('Ngenius Order Creation successful', [
                    'status' => $response->status(),
                    'response' => $response->body(),
                ]);
                return $response->json();
            }

            // If token might be expired, clear cache and retry once
            if ($response->status() === 401) {
                $this->tokenService->clearTokenCache();
                return $this->createOrder($amount, $currency);
            }

            Log::error('Ngenius Order Creation Failed', [
                'status' => $response->status(),
                'response' => $response->body(),
            ]);
            dd( $response->json());
            return false;

        } catch (\Exception $e) {
            Log::error('Ngenius Order Creation Exception', ['error' => $e->getMessage()]);
            return $e->getMessage();
//            return false;
        }
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