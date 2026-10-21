<?php

namespace App\Services;

use App\Models\Invoice;
use App\Models\InvoicePaymentAttempt;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Support\Facades\Log;

/**
 * Settles N-Genius payments that never hit the browser return URL
 * (customer closed the "Payment accepted" page without clicking Return).
 */
class NgeniusPaymentReconciliationService
{
    public function __construct(
        protected ShopOrderSettlementService $shopSettlement,
        protected InvoiceNgeniusPaymentService $invoicePayment,
        protected NgeniusPaymentService $paymentService
    ) {
    }

    /**
     * @return array{orders_settled: int, invoices_settled: int, checked: int}
     */
    public function reconcile(int $lookbackDays = 7, int $limit = 50): array
    {
        $ordersSettled = $this->reconcileShopOrders($lookbackDays, $limit);
        $invoicesSettled = $this->reconcileInvoiceAttempts($lookbackDays, $limit);

        return [
            'orders_settled' => $ordersSettled,
            'invoices_settled' => $invoicesSettled,
            'checked' => $ordersSettled + $invoicesSettled,
        ];
    }

    /**
     * Settle any unpaid online shop orders for a customer (e.g. when they revisit checkout).
     */
    public function reconcileCustomerShopOrders(int $customerId, int $lookbackDays = 7): int
    {
        $orders = $this->pendingShopOrdersQuery($lookbackDays)
            ->where('customer_id', $customerId)
            ->limit(10)
            ->get();

        $settled = 0;
        foreach ($orders as $order) {
            if ($this->trySettleShopOrder($order)) {
                $settled++;
            }
        }

        return $settled;
    }

    protected function reconcileShopOrders(int $lookbackDays, int $limit): int
    {
        $orders = $this->pendingShopOrdersQuery($lookbackDays)
            ->limit($limit)
            ->get();

        $settled = 0;
        foreach ($orders as $order) {
            if ($this->trySettleShopOrder($order)) {
                $settled++;
            }
        }

        return $settled;
    }

    protected function reconcileInvoiceAttempts(int $lookbackDays, int $limit): int
    {
        $attempts = InvoicePaymentAttempt::query()
            ->whereIn('state', [
                InvoicePaymentAttempt::STATE_CREATED,
                InvoicePaymentAttempt::STATE_PENDING,
            ])
            ->where('created_at', '>=', now()->subDays($lookbackDays))
            ->orderBy('id')
            ->limit($limit)
            ->get();

        $settled = 0;
        foreach ($attempts as $attempt) {
            if ($this->tryConfirmInvoiceAttempt($attempt)) {
                $settled++;
            }
        }

        return $settled;
    }

    protected function pendingShopOrdersQuery(int $lookbackDays)
    {
        return Order::query()
            ->where('is_deleted', 0)
            ->whereNotNull('gateway_reference')
            ->where('gateway_reference', '!=', '')
            ->where('created_at', '>=', now()->subDays($lookbackDays))
            ->where(function ($query) {
                $query->where('status', 0)
                    ->orWhereDoesntHave('invoice');
            })
            ->orderBy('id');
    }

    protected function trySettleShopOrder(Order $order): bool
    {
        $reference = trim((string) $order->gateway_reference);
        if ($reference === '') {
            return false;
        }

        if (Payment::where('gateway_order_reference', $reference)->where('is_deleted', 0)->exists()
            && Invoice::where('order_id', $order->id)->where('is_deleted', 0)->exists()
            && (int) $order->getRawOriginal('status') === 1) {
            return false;
        }

        $result = $this->shopSettlement->settlePaidOrder($order);
        if (!empty($result['success'])) {
            Log::info('N-Genius reconcile: shop order settled', [
                'order_id' => $order->id,
                'reference' => $reference,
            ]);
            return true;
        }

        return false;
    }

    protected function tryConfirmInvoiceAttempt(InvoicePaymentAttempt $attempt): bool
    {
        $reference = trim((string) $attempt->order_reference);
        if ($reference === '') {
            return false;
        }

        if (Payment::where('gateway_order_reference', $reference)->where('is_deleted', 0)->exists()) {
            if ($attempt->state !== InvoicePaymentAttempt::STATE_PAID) {
                $attempt->state = InvoicePaymentAttempt::STATE_PAID;
                $attempt->save();
            }
            return false;
        }

        $invoice = Invoice::where('id', $attempt->invoice_id)->where('is_deleted', 0)->first();
        if (!$invoice) {
            return false;
        }

        $result = $this->invoicePayment->confirmByReference(
            $invoice,
            $reference,
            'Paid via N-Genius reconciliation'
        );

        if (!empty($result['success'])) {
            Log::info('N-Genius reconcile: invoice payment settled', [
                'invoice_id' => $invoice->id,
                'reference' => $reference,
            ]);
            return true;
        }

        return false;
    }
}
