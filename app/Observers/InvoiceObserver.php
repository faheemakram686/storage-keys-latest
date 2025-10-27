<?php

namespace App\Observers;

use App\Models\Invoice;
use App\Services\ZapierService;
use AWS\CRT\Log;

class InvoiceObserver
{
    protected $zapier;

    public function __construct(ZapierService $zapier)
    {
        $this->zapier = $zapier;
    }

    public function created(Invoice $inv)
    {
        dispatch(function () use ($inv) {
            $invoice = Invoice::with('invoiceItems.productdetail', 'customer')->find($inv->id);

            if ($invoice->invoiceItems->isEmpty()) {
                \Log::info("Invoice #{$invoice->id} has no items yet. Zapier sync skipped.");
                return;
            }

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

            app(\App\Services\ZapierService::class)->send('invoice', $payload);

        })->delay(now()->addSeconds(3)); // ⏳ wait 3 seconds
    }
    public function saved(Invoice $inv)
    {
        $invoice = Invoice::with('invoiceItems.productdetail', 'customer')->find($inv->id);

        if ($invoice->invoiceItems->isEmpty()) {
            return; // Avoid sending incomplete data
        }

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

        $this->zapier->send('invoice', $payload);
    }


}
