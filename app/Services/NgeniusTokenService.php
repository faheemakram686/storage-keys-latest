<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class NgeniusTokenService
{
    protected $apiKey;
    protected $authUrl;
    protected $cacheKey = 'ngenius_access_token';

    public function __construct()
    {
        $this->apiKey = env('NGENIUS_API_KEY');
        $this->authUrl = env('NGENIUS_AUTH_URL');
    }

    public function getAccessToken()
    {
        return Cache::remember($this->cacheKey, now()->addMinutes(30), function () {
            return $this->requestNewToken();
        });
    }

    protected function requestNewToken()
    {
        try {
            $response = Http::withHeaders([
                'accept' => 'application/vnd.ni-identity.v1+json',
                'authorization' => 'Basic ' . $this->apiKey,
                'content-type' => 'application/vnd.ni-identity.v1+json',
            ])->post($this->authUrl, [
//                'realmName' => 'ni'
                'grant_type' => 'client_credentials'
            ]);

            if ($response->successful()) {
                return $response->json()['access_token'];
            }

            Log::error('Ngenius Token Request Failed', [
                'status' => $response->status(),
                'response' => $response->body(),
            ]);
            return null;

        } catch (\Exception $e) {
            Log::error('Ngenius Token Request Exception', ['error' => $e->getMessage()]);
            return null;
        }
    }

    public function clearTokenCache()
    {
        Cache::forget($this->cacheKey);
    }
}