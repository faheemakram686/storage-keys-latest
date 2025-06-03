<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class QuickBooksWebhookController extends Controller
{
    public function handle(Request $request)
    {
        // Validate the incoming webhook request
        $this->validateWebhookRequest($request);

        // If the request is valid, process the events
        $this->processWebhookEvents($request);

        return response()->json(['status' => 'success']);
    }
    private function validateWebhookRequest(Request $request)
    {
        // Ensure the request has the necessary headers
        $headers = ['intuit-signature', 'content-type'];
        foreach ($headers as $header) {
            if (!$request->hasHeader($header)) {
                Log::error("Webhook validation failed: Missing header - $header");
                abort(400, "Webhook validation failed: Missing header - $header");
            }
        }

        // Validate the HMAC signature
        $signature = $request->header('intuit-signature');
        $payload = $request->getContent();
        $secret = config('5a285d3c-981f-4004-882d-4861ee527134'); // Set this to your QuickBooks webhook secret

        $expectedSignature = base64_encode(hash_hmac('sha256', $payload, $secret, true));

        if (!hash_equals($expectedSignature, $signature)) {
            Log::error("Webhook validation failed: Invalid HMAC signature");
            abort(401, 'Webhook validation failed: Invalid HMAC signature');
        }
    }

    private function processWebhookEvents(Request $request)
    {
        $events = $request->json('data');

        foreach ($events as $event) {
            $eventName = $event['name'];
            $eventData = $event['data'];

            // Handle different event types
            switch ($eventName) {
                case 'invoice.create':
                    $this->handleInvoiceCreation($eventData);
                    break;
                case 'payment.create':
                    $this->handlePaymentReceived($eventData);
                    break;
                // Add more cases for other event types as needed
            }
        }
    }

    private function handleInvoiceCreation($eventData)
    {
        // Your logic for handling invoice creation event
        Log::info('Invoice Created: ' . json_encode($eventData));
    }

    private function handlePaymentReceived($eventData)
    {
        // Your logic for handling payment received event
        Log::info('Payment Received: ' . json_encode($eventData));
    }




}
