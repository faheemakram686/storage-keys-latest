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

    public function created(Invoice $invoice)
    {
        dispatch(function () use ($invoice) {
            $invoice->load('invoiceItems.productdetail', 'customer');

            if ($invoice->invoiceItems->isEmpty()) {
                return; // Nothing to send
            }

            $payload = [
                'invoice' => [/* ... */],
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
                }),
            ];

            app(\App\Services\ZapierService::class)->send('invoice', $payload);
        })->delay(now()->addSeconds(2));
    }

}
