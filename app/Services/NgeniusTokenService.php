<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class NgeniusTokenService
{
    protected string $cacheKey = 'ngenius_access_token';

    public function getAccessToken(): ?string
    {
        $cached = Cache::get($this->cacheKey);
        if (is_string($cached) && $cached !== '') {
            return $cached;
        }

        return $this->requestNewToken();
    }

    protected function requestNewToken(): ?string
    {
        $apiKey = trim((string) config('services.ngenius.api_key'));
        $authUrl = trim((string) config('services.ngenius.auth_url'));
        $realm = trim((string) config('services.ngenius.realm', 'ni'));

        if ($apiKey === '' || $authUrl === '') {
            Log::error('Ngenius Token Request Failed: missing API key or auth URL');
            return null;
        }

        try {
            $response = Http::withHeaders([
                'Accept' => 'application/vnd.ni-identity.v1+json',
                'Authorization' => 'Basic ' . $apiKey,
                'Content-Type' => 'application/vnd.ni-identity.v1+json',
            ])->post($authUrl, [
                'grant_type' => 'client_credentials',
                'realm' => $realm !== '' ? $realm : 'ni',
            ]);

            if (!$response->successful()) {
                Log::error('Ngenius Token Request Failed', [
                    'status' => $response->status(),
                ]);
                return null;
            }

            $data = $response->json();
            $accessToken = $data['access_token'] ?? null;
            if (!is_string($accessToken) || $accessToken === '') {
                Log::error('Ngenius Token Request Failed: access_token missing');
                return null;
            }

            $expiresIn = (int) ($data['expires_in'] ?? 300);
            $ttlSeconds = max(30, $expiresIn - 30);
            Cache::put($this->cacheKey, $accessToken, now()->addSeconds($ttlSeconds));

            return $accessToken;
        } catch (\Exception $e) {
            Log::error('Ngenius Token Request Exception', ['error' => $e->getMessage()]);
            return null;
        }
    }

    public function clearTokenCache(): void
    {
        Cache::forget($this->cacheKey);
    }
}
