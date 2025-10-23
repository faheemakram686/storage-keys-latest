<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ZapierService
{
    /**
     * Send any data to Zapier based on the given type (customer, invoice, etc.)
     */
    public function send(string $type, array $data): bool
    {
        $webhook = config("zapier.webhooks.{$type}");

        if (!$webhook) {
            Log::warning("No Zapier webhook configured for type: {$type}");
            return false;
        }

        try {
            $response = Http::post($webhook, $data);

            if ($response->failed()) {
                Log::error("Zapier Webhook Error ({$type}): " . $response->body());
                return false;
            }

            Log::info("Zapier Webhook Success ({$type})", $data);
            return true;

        } catch (\Throwable $e) {
            Log::error("Zapier Exception ({$type}): " . $e->getMessage());
            return false;
        }
    }
}
