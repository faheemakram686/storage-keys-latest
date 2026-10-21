<?php

namespace App\Http\Controllers;

use App\Models\NgeniusWebhookEvent;
use App\Models\Order;
use App\Services\InvoiceNgeniusPaymentService;
use App\Services\ShopOrderSettlementService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class NgeniusWebhookController extends Controller
{
    public function __construct(
        protected InvoiceNgeniusPaymentService $invoicePaymentService,
        protected ShopOrderSettlementService $shopSettlementService
    ) {
    }

    public function handle(Request $request)
    {
        if (!$this->isAuthorized($request)) {
            Log::warning('N-Genius webhook rejected: invalid secret header');
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $payload = $request->all();
        $eventId = (string) ($payload['eventId'] ?? '');
        $eventName = (string) ($payload['eventName'] ?? '');
        $orderReference = (string) data_get($payload, 'order.reference', '');

        Log::info('N-Genius webhook received', [
            'eventId' => $eventId !== '' ? $eventId : null,
            'eventName' => $eventName !== '' ? $eventName : null,
            'orderReference' => $orderReference !== '' ? $orderReference : null,
        ]);

        if ($eventId === '') {
            return response()->json(['message' => 'Accepted'], 200);
        }

        $existing = NgeniusWebhookEvent::where('event_id', $eventId)->first();
        if ($existing && $existing->status === NgeniusWebhookEvent::STATUS_PROCESSED) {
            return response()->json(['message' => 'Duplicate'], 200);
        }

        $event = $existing ?: NgeniusWebhookEvent::create([
            'event_id' => $eventId,
            'event_name' => $eventName ?: null,
            'order_reference' => $orderReference ?: null,
            'status' => NgeniusWebhookEvent::STATUS_RECEIVED,
        ]);

        if ($existing && $existing->status === NgeniusWebhookEvent::STATUS_FAILED) {
            $event->status = NgeniusWebhookEvent::STATUS_RECEIVED;
            $event->save();
        }

        if (!in_array($eventName, ['PURCHASED', 'CAPTURED'], true) || $orderReference === '') {
            $event->markProcessed();
            return response()->json(['message' => 'Accepted'], 200);
        }

        $resolved = $this->invoicePaymentService->resolveByOrderReference($orderReference);
        $invoice = $resolved['invoice'];

        if (!$invoice) {
            $shopOrder = Order::query()
                ->where('gateway_reference', $orderReference)
                ->where('is_deleted', 0)
                ->first();

            if ($shopOrder) {
                $settled = $this->shopSettlementService->settlePaidOrder($shopOrder);
                if (!empty($settled['success'])) {
                    $event->markProcessed();
                    return response()->json(['message' => 'Accepted'], 200);
                }

                Log::warning('N-Genius webhook: shop order settlement failed', [
                    'orderReference' => $orderReference,
                    'order_id' => $shopOrder->id,
                    'message' => $settled['message'] ?? null,
                ]);
                $event->markFailed();
                return response()->json(['message' => 'Retry later'], 500);
            }

            // Unknown refs — acknowledge so N-Genius does not retry forever.
            Log::info('N-Genius webhook: no invoice or shop order for order reference', [
                'orderReference' => $orderReference,
            ]);
            $event->markProcessed();
            return response()->json(['message' => 'Accepted'], 200);
        }

        // Webhook body is only a signal — always re-fetch live order status.
        $result = $this->invoicePaymentService->confirmByReference(
            $invoice,
            $orderReference,
            'Paid via N-Genius webhook (' . $eventName . ')'
        );

        if (!empty($result['success'])) {
            $event->markProcessed();
            return response()->json(['message' => 'Accepted'], 200);
        }

        if (($result['status'] ?? '') === 'error' || ($result['message'] ?? '') === 'Payment save failed') {
            $event->markFailed();
            return response()->json(['message' => 'Retry later'], 500);
        }

        // Declined / cancelled / not completed — do not retry forever.
        $event->markProcessed();
        return response()->json(['message' => 'Accepted'], 200);
    }

    protected function isAuthorized(Request $request): bool
    {
        $headerName = trim((string) config('services.ngenius.webhook_header', 'X-Webhook-Token'));
        $secret = (string) config('services.ngenius.webhook_secret');

        if ($secret === '') {
            return false;
        }

        $provided = (string) $request->header($headerName, '');
        return hash_equals($secret, $provided);
    }
}
