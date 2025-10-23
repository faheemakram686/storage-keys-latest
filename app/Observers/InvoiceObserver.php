<?php

namespace App\Observers;

use App\Models\Invoice;
use App\Services\ZapierService;

class InvoiceObserver
{
    protected $zapier;

    public function __construct(ZapierService $zapier)
    {
        $this->zapier = $zapier;
    }

    public function created(Invoice $invoice)
    {
        $this->zapier->send('invoice', [
            'id' => $invoice->id,
            'invoice_no' => $invoice->invoice_no,
            'customer_id' => $invoice->customer_id,
            'sub_total' => $invoice->sub_total,
            'vat' => $invoice->vat,
            'grand_total' => $invoice->grand_total,
            'invoice_date' => $invoice->invoice_date,
            'due_date' => $invoice->due_date,
            'status' => $invoice->status,
        ]);
    }
}
