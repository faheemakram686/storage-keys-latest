<?php

namespace App\Services;

use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Order;
use App\Models\Payment;
use App\Repo\PaymentClass;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ShopOrderSettlementService
{
    public function __construct(
        protected NgeniusPaymentService $paymentService
    ) {
    }

    /**
     * Create one paid invoice and one payment for a shop order confirmed by N-Genius.
     * Safe to call repeatedly — reuses existing invoice/payment by order_id / gateway ref.
     *
     * @return array{success: bool, invoice: Invoice|null, payment: Payment|null, message: string}
     */
    public function settlePaidOrder(Order $order, ?array $gatewayOrder = null): array
    {
        $reference = trim((string) ($order->gateway_reference ?? ''));
        if ($reference === '') {
            return [
                'success' => false,
                'invoice' => null,
                'payment' => null,
                'message' => 'Order has no gateway reference',
            ];
        }

        if ($gatewayOrder === null) {
            $fetched = $this->paymentService->getOrderStatus($reference);
            if (!$fetched || !is_array($fetched)) {
                return [
                    'success' => false,
                    'invoice' => null,
                    'payment' => null,
                    'message' => 'Could not load gateway order status',
                ];
            }
            $gatewayOrder = $fetched;
        }

        if (!$this->paymentService->isPaid($gatewayOrder)) {
            return [
                'success' => false,
                'invoice' => null,
                'payment' => null,
                'message' => 'Payment not completed',
            ];
        }

        $currency = $this->paymentService->extractCurrency($gatewayOrder) ?: 'AED';
        if ($currency !== 'AED') {
            Log::warning('Shop order settlement currency mismatch', [
                'order_id' => $order->id,
                'currency' => $currency,
                'reference' => $reference,
            ]);
            return [
                'success' => false,
                'invoice' => null,
                'payment' => null,
                'message' => 'Unsupported currency',
            ];
        }

        $capturedMinor = $this->paymentService->extractCapturedAmount($gatewayOrder);
        $amountReceived = $capturedMinor !== null
            ? round($capturedMinor / 100, 2)
            : (float) $order->total_amount;

        if ($amountReceived <= 0) {
            return [
                'success' => false,
                'invoice' => null,
                'payment' => null,
                'message' => 'Invalid captured amount',
            ];
        }

        try {
            return DB::transaction(function () use ($order, $reference, $amountReceived) {
                $locked = Order::where('id', $order->id)->lockForUpdate()->first();
                if (!$locked) {
                    return [
                        'success' => false,
                        'invoice' => null,
                        'payment' => null,
                        'message' => 'Order not found',
                    ];
                }

                $invoice = Invoice::query()
                    ->where('order_id', $locked->id)
                    ->where('is_deleted', 0)
                    ->lockForUpdate()
                    ->first();

                if (!$invoice) {
                    $invoice = $this->createInvoiceFromOrder($locked, $reference);
                } elseif (empty($invoice->invoice_ref)) {
                    $invoice->invoice_ref = $reference;
                    $invoice->save();
                }

                $payment = Payment::query()
                    ->where('gateway_order_reference', $reference)
                    ->where('is_deleted', 0)
                    ->lockForUpdate()
                    ->first();

                if (!$payment) {
                    $paymentPayload = (object) [
                        'invoice_ref' => $reference,
                        'invoice_id' => $invoice->id,
                        'customer_id' => $locked->customer_id,
                        'contract_id' => null,
                        'order_id' => $locked->id,
                        'payment_method' => 3,
                        'payment_date' => now(),
                        'amount_received' => $amountReceived,
                        'note' => 'Paid via shop checkout',
                        'gateway_order_reference' => $reference,
                    ];

                    $paymentResponse = (new PaymentClass())->savePayment($paymentPayload);
                    $responseData = $paymentResponse ? $paymentResponse->getData() : null;
                    $payment = $responseData->data ?? null;

                    if (!$payment) {
                        throw new \RuntimeException('Payment save failed');
                    }
                }

                if ((int) $locked->getRawOriginal('status') !== 1) {
                    $locked->status = 1;
                    $locked->save();
                }

                $invoice->load(array_keys(Invoice::paymentStatusRelations()));
                $invoice->syncPaymentStatus();

                return [
                    'success' => true,
                    'invoice' => $invoice->fresh(),
                    'payment' => $payment instanceof Payment ? $payment : Payment::find($payment->id ?? null),
                    'message' => 'Order settled',
                ];
            });
        } catch (\Throwable $e) {
            Log::error('Shop order settlement failed', [
                'order_id' => $order->id,
                'reference' => $reference,
                'error' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'invoice' => null,
                'payment' => null,
                'message' => 'Settlement failed',
            ];
        }
    }

    /**
     * Backfill when staff open an order, or when settlement never ran because
     * the customer closed the N-Genius page without clicking Return.
     */
    public function settleIfMissingInvoice(Order $order): array
    {
        if (trim((string) ($order->gateway_reference ?? '')) === '') {
            return [
                'success' => false,
                'invoice' => null,
                'payment' => null,
                'message' => 'Order has no gateway reference',
            ];
        }

        $exists = Invoice::query()
            ->where('order_id', $order->id)
            ->where('is_deleted', 0)
            ->exists();

        $isActive = (int) $order->getRawOriginal('status') === 1;

        if ($exists && $isActive) {
            return [
                'success' => true,
                'invoice' => Invoice::where('order_id', $order->id)->where('is_deleted', 0)->first(),
                'payment' => null,
                'message' => 'Invoice already exists',
            ];
        }

        // Unpaid in DB (or Active but missing invoice) — ask N-Genius and settle if paid.
        return $this->settlePaidOrder($order);
    }

    protected function createInvoiceFromOrder(Order $order, string $reference): Invoice
    {
        $order->loadMissing('orderItems');
        $orderDate = optional($order->created_at)->toDateString() ?: now()->toDateString();
        $total = (float) $order->total_amount;

        $invoice = new Invoice();
        $invoice->customer_id = $order->customer_id;
        $invoice->type = 'order';
        $invoice->order_id = $order->id;
        $invoice->invoice_no = Invoice::generateInvoiceNumber();
        $invoice->invoice_date = $orderDate;
        $invoice->due_date = $orderDate;
        $invoice->payment_method = 'opm';
        // One-time shop checkout — never recurring.
        $invoice->recurring = '0';
        $invoice->no_cycle = null;
        $invoice->next_recurring_date = null;
        $invoice->status = Invoice::PAYMENT_PAID;
        $invoice->invoice_ref = $reference;
        $invoice->sub_total = $total;
        $invoice->vat = 0;
        $invoice->grand_total = $total;
        $invoice->note = 'Shop order #' . $order->id;
        $invoice->is_deleted = 0;
        $invoice->save();

        foreach ($order->orderItems as $item) {
            $invoiceItem = new InvoiceItem();
            $invoiceItem->invoice_id = $invoice->id;
            $invoiceItem->item_id = $item->product_id;
            $invoiceItem->category = 'product';
            $invoiceItem->item_name = (string) ($item->item_name ?: optional($item->productdetail)->p_name ?: 'Item');
            $invoiceItem->quantity = (int) $item->qty;
            $invoiceItem->unit = '';
            $invoiceItem->unit_price = (float) $item->price;
            $invoiceItem->total_price = (float) $item->total;
            $invoiceItem->save();
        }

        return $invoice;
    }
}
