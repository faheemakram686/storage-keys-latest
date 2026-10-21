<?php
namespace App\Repo;

use App\Enums\StorageUnitStatus;
use App\Models\Addon;
use App\Models\Contract;
use App\Models\Customer;
use App\Models\Estimate;
use App\Models\Invoice;
use App\Models\Lead;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Product;
use App\Models\StorageUnit;
use App\Repo\Interfaces\AdminDashboardInterface;
use Carbon\Carbon;
class AdminDashboardClass implements AdminDashboardInterface
{
    public function getLeadsCount()
    {
        return Lead::query()->where('is_deleted', 0)->count();
    }

    public function getCustomersCount()
    {
        return Customer::query()->where('is_deleted', 0)->count();
    }

    public function getEstimatesCount()
    {
        return Estimate::query()->where('is_deleted', 0)->count();
    }

    public function getContractCount()
    {
        return Contract::query()->where('is_deleted', 0)->count();
    }

    public function getProductsCount()
    {
        return Product::query()->where('is_deleted', 0)->count();
    }

    public function getAddonsCount()
    {
        return Addon::query()->where('is_deleted', 0)->count();
    }

    public function getStorageUnitsCount()
    {
        return StorageUnit::query()->where('is_deleted', 0)->count();
    }

    public function getCollectedThisMonth(): float
    {
        return (float) Payment::query()
            ->where('is_deleted', 0)
            ->whereNotNull('payment_date')
            ->whereBetween('payment_date', [
                Carbon::now()->startOfMonth()->toDateString(),
                Carbon::now()->endOfMonth()->toDateString(),
            ])
            ->sum('amount_received');
    }

    public function getOutstandingBalance(): float
    {
        $invoices = Invoice::query()
            ->with(Invoice::paymentStatusRelations())
            ->where('is_deleted', 0)
            ->get(['id', 'grand_total']);

        return round($invoices->sum(fn (Invoice $invoice) => $invoice->balanceAmount()), 2);
    }

    public function getOverdueInvoiceCount(): int
    {
        $today = Carbon::today()->toDateString();

        return Invoice::query()
            ->with(Invoice::paymentStatusRelations())
            ->where('is_deleted', 0)
            ->whereNotNull('due_date')
            ->where('due_date', '<', $today)
            ->get(['id', 'grand_total', 'due_date'])
            ->filter(fn (Invoice $invoice) => $invoice->balanceAmount() > 0)
            ->count();
    }

    public function getOccupancyPercent(): float
    {
        $total = $this->getStorageUnitsCount();
        if ($total === 0) {
            return 0.0;
        }

        $occupied = StorageUnit::query()
            ->where('is_deleted', 0)
            ->where('status', StorageUnitStatus::OCCUPIED)
            ->count();

        return round(($occupied / $total) * 100, 1);
    }

    public function getSalesStatistics(string $period = 'weekly'): array
    {
        $period = in_array($period, ['daily', 'weekly', 'monthly'], true) ? $period : 'weekly';

        [$buckets, $start, $end] = $this->buildSalesBuckets($period);

        $payments = Payment::query()
            ->where('is_deleted', 0)
            ->whereNotNull('payment_date')
            ->whereBetween('payment_date', [$start->toDateString(), $end->toDateString()])
            ->get(['payment_date', 'amount_received']);

        $invoices = Invoice::query()
            ->where('is_deleted', 0)
            ->whereNotNull('invoice_date')
            ->whereBetween('invoice_date', [$start->toDateString(), $end->toDateString()])
            ->get(['invoice_date', 'grand_total']);

        $collected = $buckets;
        $invoiced = $buckets;

        foreach ($payments as $payment) {
            $key = $this->periodKey(Carbon::parse($payment->payment_date), $period);
            if (array_key_exists($key, $collected)) {
                $collected[$key] += (float) $payment->amount_received;
            }
        }

        foreach ($invoices as $invoice) {
            $key = $this->periodKey(Carbon::parse($invoice->invoice_date), $period);
            if (array_key_exists($key, $invoiced)) {
                $invoiced[$key] += (float) $invoice->grand_total;
            }
        }

        $labels = array_keys($buckets);
        $collectedValues = array_map(fn ($v) => round($v, 2), array_values($collected));
        $invoicedValues = array_map(fn ($v) => round($v, 2), array_values($invoiced));
        $maxValue = max(array_merge([0], $collectedValues, $invoicedValues));

        return [
            'period' => $period,
            'labels' => $labels,
            'collected' => $collectedValues,
            'invoiced' => $invoicedValues,
            'start_label' => $start->format('d M, Y'),
            'end_label' => $end->format('d M, Y'),
            'step_size' => $this->chartStepSize($maxValue),
        ];
    }

    public function getLeadStatistics(): array
    {
        $colors = ['#816bff', '#13c9f2', '#ff82b7', '#eb6459', '#f58320', '#9d72ff'];

        $rows = Lead::query()
            ->leftJoin('lead_statuses', 'leads.status', '=', 'lead_statuses.id')
            ->where('leads.is_deleted', 0)
            ->selectRaw("COALESCE(lead_statuses.title, 'Unassigned') as label, COUNT(*) as total")
            ->groupByRaw("COALESCE(lead_statuses.title, 'Unassigned')")
            ->orderByDesc('total')
            ->get();

        $labels = [];
        $data = [];
        $background = [];

        foreach ($rows as $index => $row) {
            $labels[] = $row->label;
            $data[] = (int) $row->total;
            $background[] = $colors[$index % count($colors)];
        }

        if (empty($labels)) {
            $labels = ['No leads'];
            $data = [0];
            $background = ['#816bff'];
        }

        return [
            'labels' => $labels,
            'data' => $data,
            'background' => $background,
        ];
    }

    public function getStorageUnitStatistics(): array
    {
        $statusMeta = [
            StorageUnitStatus::OCCUPIED => ['label' => 'Occupied', 'color' => '#816bff'],
            StorageUnitStatus::VACANT => ['label' => 'Vacant', 'color' => '#13c9f2'],
            StorageUnitStatus::BOOKED => ['label' => 'Booked', 'color' => '#ff82b7'],
            StorageUnitStatus::BOOKED_NOT_PAID => ['label' => 'Booked not paid', 'color' => '#eb6459'],
            StorageUnitStatus::UNDER_MAINTENANCE => ['label' => 'Under maintenance', 'color' => '#f58320'],
        ];

        $counts = array_fill_keys(array_keys($statusMeta), 0);

        $units = StorageUnit::query()
            ->where('is_deleted', 0)
            ->get(['status', 'is_maintenance']);

        foreach ($units as $unit) {
            $status = StorageUnitStatus::normalize($unit->status);
            if ($unit->is_maintenance || $status === StorageUnitStatus::UNDER_MAINTENANCE) {
                $counts[StorageUnitStatus::UNDER_MAINTENANCE]++;
                continue;
            }
            if (!isset($counts[$status])) {
                $counts[StorageUnitStatus::VACANT]++;
                continue;
            }
            $counts[$status]++;
        }

        $labels = [];
        $data = [];
        $background = [];

        foreach ($statusMeta as $key => $meta) {
            $labels[] = $meta['label'];
            $data[] = (int) $counts[$key];
            $background[] = $meta['color'];
        }

        return [
            'labels' => $labels,
            'data' => $data,
            'background' => $background,
        ];
    }

    public function getInvoicePaymentStatistics(): array
    {
        $paid = 0;
        $partial = 0;
        $pending = 0;

        $invoices = Invoice::query()
            ->with(Invoice::paymentStatusRelations())
            ->where('is_deleted', 0)
            ->get(['id', 'grand_total']);

        foreach ($invoices as $invoice) {
            $status = $invoice->resolvePaymentStatusValue();
            if ($status === Invoice::PAYMENT_PAID) {
                $paid++;
            } elseif ($status === Invoice::PAYMENT_PARTIAL) {
                $partial++;
            } else {
                $pending++;
            }
        }

        return [
            'labels' => ['Paid', 'Partial', 'Pending'],
            'data' => [$paid, $partial, $pending],
            'background' => ['#816bff', '#13c9f2', '#ff82b7'],
        ];
    }

    public function getRecentOrders(int $limit = 8)
    {
        return Order::with('customer.primaryContact')
            ->where('is_deleted', 0)
            ->orderByDesc('id')
            ->limit($limit)
            ->get();
    }

    public function getRecentInvoices(int $limit = 8)
    {
        return Invoice::with(array_merge(
            ['customer.primaryContact'],
            Invoice::paymentStatusRelations()
        ))
            ->where('is_deleted', 0)
            ->orderByDesc('id')
            ->limit($limit)
            ->get();
    }

    public function getContractsEndingSoon(int $days = 30, int $limit = 8)
    {
        $today = Carbon::today();
        $end = $today->copy()->addDays($days);

        return Contract::with('customer.primaryContact')
            ->where('is_deleted', 0)
            ->whereNotNull('end_date')
            ->where('end_date', '!=', '')
            ->orderBy('end_date')
            ->get()
            ->filter(function (Contract $contract) use ($today, $end) {
                try {
                    $endDate = Carbon::parse($contract->end_date)->startOfDay();
                } catch (\Throwable $e) {
                    return false;
                }

                return $endDate->between($today, $end, true);
            })
            ->take($limit)
            ->values();
    }

    private function buildSalesBuckets(string $period): array
    {
        $buckets = [];
        $end = Carbon::today()->endOfDay();

        if ($period === 'daily') {
            $start = Carbon::today()->subDays(13)->startOfDay();
            $cursor = $start->copy();
            while ($cursor->lte($end)) {
                $buckets[$cursor->format('d M')] = 0.0;
                $cursor->addDay();
            }

            return [$buckets, $start, $end];
        }

        if ($period === 'monthly') {
            $start = Carbon::today()->subMonths(11)->startOfMonth();
            $cursor = $start->copy();
            $rangeEnd = Carbon::today()->endOfMonth();
            while ($cursor->lte($rangeEnd)) {
                $buckets[$cursor->format('M Y')] = 0.0;
                $cursor->addMonth();
            }

            return [$buckets, $start, $end];
        }

        // weekly — last 8 weeks (including current)
        $start = Carbon::today()->subWeeks(7)->startOfWeek();
        $cursor = $start->copy();
        while ($cursor->lte($end)) {
            $weekEnd = $cursor->copy()->endOfWeek();
            $label = $cursor->format('d M') . ' - ' . $weekEnd->format('d M');
            $buckets[$label] = 0.0;
            $cursor->addWeek();
        }

        return [$buckets, $start, $end];
    }

    private function periodKey(Carbon $date, string $period): string
    {
        if ($period === 'daily') {
            return $date->format('d M');
        }

        if ($period === 'monthly') {
            return $date->format('M Y');
        }

        $weekStart = $date->copy()->startOfWeek();
        $weekEnd = $date->copy()->endOfWeek();

        return $weekStart->format('d M') . ' - ' . $weekEnd->format('d M');
    }

    private function chartStepSize(float $maxValue): int
    {
        if ($maxValue <= 0) {
            return 100;
        }

        $rough = $maxValue / 4;
        $magnitude = pow(10, floor(log10($rough)));
        $normalized = $rough / $magnitude;

        if ($normalized <= 1) {
            $step = 1;
        } elseif ($normalized <= 2) {
            $step = 2;
        } elseif ($normalized <= 5) {
            $step = 5;
        } else {
            $step = 10;
        }

        return (int) max(1, $step * $magnitude);
    }
}
