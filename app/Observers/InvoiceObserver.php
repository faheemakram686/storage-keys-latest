<?php

namespace App\Observers;

use App\Models\Invoice;
use App\Services\ZapierService;
use Illuminate\Support\Facades\Log;

class InvoiceObserver
{
    protected $zapier;

    public function __construct(ZapierService $zapier)
    {
        $this->zapier = $zapier;
    }

    public function created(Invoice $invoice)
    {
        // Delay execution to ensure related items are saved
        dispatch(function () use ($invoice) {
            // Reload relations after delay
            $invoice->load('invoiceItems.productdetail', 'customer');

            // Skip if invoice has no items
            if ($invoice->invoiceItems->isEmpty()) {
                Log::info("Zapier Sync Skipped: Invoice #{$invoice->id} has no items.");
                return;
            }

            // Build payload
            $payload = [
                'invoice' => [
                    'id' => $invoice->id,
                    'customer_id' => optional($invoice->customer)->q_customer_id,
                    'type' => $invoice->type,
                    'contract_id' => $invoice->contract_id,
                    'order_id' => $invoice->order_id,
                    'recurring' => $invoice->recurring,
                    'no_cycle' => $invoice->no_cycle,
                    'duration' => $invoice->duration,
                    'duration_type' => $invoice->duration_type,
                    'invoice_date' => $invoice->invoice_date,
                    'invoice_no' => $invoice->invoice_no,
                    'user_id' => $invoice->user_id,
                    'sub_total' => $invoice->sub_total,
                    'vat' => $invoice->vat,
                    'grand_total' => $invoice->grand_total,
                    'due_date' => $invoice->due_date,
                    'note' => $invoice->note,
                    'payment_method' => $invoice->payment_method,
                    'status' => $invoice->status,
                ],
                'invoiceItems' => $invoice->invoiceItems->map(function ($item) {
                    return [
                        'id' => $item->id,
                        'invoice_id' => $item->invoice_id,
                        'item_id' => optional($item->productdetail)->q_product_id,
                        'category' => $item->category,
                        'item_name' => $item->item_name,
                        'quantity' => $item->quantity,
                        'unit' => $item->unit,
                        'unit_price' => $item->unit_price,
                        'total_price' => $item->total_price,
                    ];
                })->toArray(),
            ];

            // Send to Zapier
            try {
                app(ZapierService::class)->send('invoice', $payload);
                Log::info("Zapier Sync Success: Invoice #{$invoice->id} sent.", ['payload' => $payload]);
            } catch (\Throwable $e) {
                Log::error("Zapier Sync Failed: Invoice #{$invoice->id}", [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                ]);
            }
        })->delay(now()->addSeconds(2));
    }
}
