<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\Customer;

class ZapierService
{
    /**
     * Send new customer data to Zapier
     */
    public function sendCustomerCreated(Customer $customer)
    {

        $url = config('services.zapier.customer_webhook_url');

        $payload = [
            'event' => 'customer_created',
            'data' => [
                'id' => $customer->id,
                'customer_name' => $customer->customer_name,
                'email' => $customer->email,
                'phone' => $customer->phone,
                'address' => $customer->address,
                'created_at' => $customer->created_at,
            ],
        ];

        try {
            $response = Http::post($url, $payload);

            Log::info('✅ Customer sent to Zapier', [
                'status' => $response->status(),
                'url' => $url,
                'payload' => $payload,
                'response' => $response->body(),
            ]);

            return $response->successful();
        } catch (\Throwable $e) {
            Log::error('❌ Failed to send customer to Zapier', [
                'error' => $e->getMessage(),
                'payload' => $payload,
            ]);

            return false;
        }
    }
}
