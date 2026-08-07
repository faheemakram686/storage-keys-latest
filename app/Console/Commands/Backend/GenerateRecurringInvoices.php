<?php

namespace App\Console\Commands\Backend;

use App\Models\Contract;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Services\StorageUnitStatusService;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class GenerateRecurringInvoices extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'invoice:recurring';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate child invoices for due recurring invoices';

    public function handle(StorageUnitStatusService $unitStatus): int
    {
        $today = Carbon::today()->format('Y-m-d');

        $parents = Invoice::with('invoiceItems')
            ->where('is_deleted', 0)
            ->whereNotNull('recurring')
            ->where('recurring', '!=', '0')
            ->whereNotNull('next_recurring_date')
            ->whereDate('next_recurring_date', '<=', $today)
            ->where(function ($query) {
                $query->where('no_cycle', 'infinity')
                    ->orWhereRaw('CAST(no_cycle AS UNSIGNED) > 0');
            })
            ->get();

        if ($parents->isEmpty()) {
            $this->info('No recurring invoices due today.');

            return self::SUCCESS;
        }

        $generated = 0;

        foreach ($parents as $parent) {
            try {
                DB::transaction(function () use ($parent, $unitStatus, &$generated) {
                    $dueDate = $parent->next_recurring_date;
                    $dayGap = 0;

                    if ($parent->invoice_date && $parent->due_date) {
                        $dayGap = Carbon::parse($parent->invoice_date)
                            ->diffInDays(Carbon::parse($parent->due_date), false);
                    }

                    $child = new Invoice();
                    $child->customer_id = $parent->customer_id;
                    $child->type = $parent->type;
                    $child->contract_id = $parent->contract_id;
                    $child->order_id = $parent->order_id;
                    $child->user_id = $parent->user_id;
                    $child->recurring = '0';
                    $child->no_cycle = null;
                    $child->duration = null;
                    $child->duration_type = null;
                    $child->next_recurring_date = null;
                    $child->parent_invoice_id = $parent->id;
                    $child->invoice_no = Invoice::generateInvoiceNumber();
                    $child->invoice_date = $dueDate;
                    $child->due_date = $dayGap > 0
                        ? Carbon::parse($dueDate)->addDays($dayGap)->format('Y-m-d')
                        : $dueDate;
                    $child->sub_total = $parent->sub_total;
                    $child->vat = $parent->vat;
                    $child->vat_type = $parent->vat_type;
                    $child->grand_total = $parent->grand_total;
                    $child->note = $parent->note;
                    $child->remarks = $parent->remarks;
                    $child->payment_method = $parent->payment_method;
                    $child->status = 1;
                    $child->payment_status = Invoice::PAYMENT_PENDING;
                    $child->is_deleted = 0;
                    $child->save();

                    foreach ($parent->invoiceItems as $item) {
                        $invoiceItem = new InvoiceItem();
                        $invoiceItem->invoice_id = $child->id;
                        $invoiceItem->item_id = $item->item_id;
                        $invoiceItem->category = $item->category;
                        $invoiceItem->item_name = $item->item_name;
                        $invoiceItem->quantity = $item->quantity;
                        $invoiceItem->unit = $item->unit;
                        $invoiceItem->unit_price = $item->unit_price;
                        $invoiceItem->total_price = $item->total_price;
                        $invoiceItem->save();
                    }

                    if ($parent->no_cycle !== 'infinity') {
                        $parent->no_cycle = (string) max(0, ((int) $parent->no_cycle) - 1);
                    }

                    if ($parent->isRecurringActive()) {
                        $parent->next_recurring_date = $parent->calculateNextRecurringDate($dueDate);
                    } else {
                        $parent->next_recurring_date = null;
                    }

                    $parent->save();

                    if ($child->contract_id) {
                        $contract = Contract::find($child->contract_id);
                        if ($contract) {
                            $unitStatus->recalculateForContract($contract, 'invoice.recurring');
                        }
                    }

                    $generated++;
                    Log::info("Recurring invoice generated: parent #{$parent->id} -> child #{$child->id} ({$child->invoice_no})");
                });
            } catch (\Throwable $e) {
                Log::error("Failed generating recurring invoice for parent #{$parent->id}: ".$e->getMessage(), [
                    'exception' => $e,
                ]);
                $this->error("Failed for invoice #{$parent->id}: ".$e->getMessage());
            }
        }

        $this->info("Generated {$generated} recurring invoice(s).");

        return self::SUCCESS;
    }
}
