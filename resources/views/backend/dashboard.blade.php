@extends('backend.layouts.app')

@section('title', '| Dashboard')

@section('content')
    <div class="nk-content-body">
        <div class="nk-block-head nk-block-head-sm">
            <div class="nk-block-between">
                <div class="nk-block-head-content">
                    <h3 class="nk-block-title page-title">Dashboard</h3>
                </div>
            </div>
        </div>

        @isset($data)
            <div class="nk-block" id="home_data">
                {{-- Count cards --}}
                <div class="row mb-4">
                    <div class="col-lg-12">
                        <div class="row">
                            <div class="col-lg-3 mb-3">
                                <div class="card">
                                    <div class="card-body p-0">
                                        <div class="media p-3">
                                            <a href="{{ route('leads.index') }}" class="media-body" style="display: contents;">
                                                <div class="media-body">
                                                    <h6 class="title">Total Leads</h6>
                                                    <h3 class="mb-0 mt-2">{{ $data['leads'] ?: 0 }}</h3>
                                                </div>
                                                <div class="align-self-center text-center analytics-icon" style="font-size: 70px;">
                                                    <em class="icon ni ni-calendar-check text-info"></em>
                                                </div>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 mb-3">
                                <div class="card">
                                    <div class="card-body p-0">
                                        <div class="media p-3">
                                            <a href="{{ route('estimate.index') }}" class="media-body" style="display: contents;">
                                                <div class="media-body">
                                                    <h6 class="title">Total Estimates</h6>
                                                    <h3 class="mb-0 mt-2">{{ $data['estimates'] ?: 0 }}</h3>
                                                </div>
                                                <div class="align-self-center text-center analytics-icon" style="font-size: 70px;">
                                                    <em class="icon ni ni-calendar-booking text-info"></em>
                                                </div>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 mb-3">
                                <div class="card">
                                    <div class="card-body p-0">
                                        <div class="media p-3">
                                            <a href="{{ route('contract.index') }}" class="media-body" style="display: contents;">
                                                <div class="media-body">
                                                    <h6 class="title">Total Contracts</h6>
                                                    <h3 class="mb-0 mt-2">{{ $data['contracts'] ?: 0 }}</h3>
                                                </div>
                                                <div class="align-self-center text-center analytics-icon" style="font-size: 70px;">
                                                    <em class="icon ni ni-cc-off text-info"></em>
                                                </div>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 mb-3">
                                <div class="card">
                                    <div class="card-body p-0">
                                        <div class="media p-3">
                                            <a href="{{ route('customer.index') }}" class="media-body" style="display: contents;">
                                                <div class="media-body">
                                                    <h6 class="title">Total Customers</h6>
                                                    <h3 class="mb-0 mt-2">{{ $data['customers'] ?: 0 }}</h3>
                                                </div>
                                                <div class="align-self-center text-center analytics-icon" style="font-size: 70px;">
                                                    <em class="icon ni ni-users-fill text-info"></em>
                                                </div>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Money / ops cards --}}
                <div class="row mb-4">
                    <div class="col-lg-12">
                        <div class="row">
                            <div class="col-lg-3 mb-3">
                                <div class="card">
                                    <div class="card-body p-0">
                                        <div class="media p-3">
                                            <a href="{{ route('invoice.index') }}" class="media-body" style="display: contents;">
                                                <div class="media-body">
                                                    <h6 class="title">Collected This Month</h6>
                                                    <h3 class="mb-0 mt-2">{{ number_format($data['collected_this_month'] ?? 0, 2) }} <small class="fs-14px">AED</small></h3>
                                                </div>
                                                <div class="align-self-center text-center analytics-icon" style="font-size: 70px;">
                                                    <em class="icon ni ni-wallet-fill text-info"></em>
                                                </div>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 mb-3">
                                <div class="card">
                                    <div class="card-body p-0">
                                        <div class="media p-3">
                                            <a href="{{ route('invoice.index') }}" class="media-body" style="display: contents;">
                                                <div class="media-body">
                                                    <h6 class="title">Outstanding Balance</h6>
                                                    <h3 class="mb-0 mt-2">{{ number_format($data['outstanding_balance'] ?? 0, 2) }} <small class="fs-14px">AED</small></h3>
                                                </div>
                                                <div class="align-self-center text-center analytics-icon" style="font-size: 70px;">
                                                    <em class="icon ni ni-coins text-info"></em>
                                                </div>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 mb-3">
                                <div class="card">
                                    <div class="card-body p-0">
                                        <div class="media p-3">
                                            <a href="{{ route('invoice.index') }}" class="media-body" style="display: contents;">
                                                <div class="media-body">
                                                    <h6 class="title">Overdue Invoices</h6>
                                                    <h3 class="mb-0 mt-2">{{ $data['overdue_invoices'] ?? 0 }}</h3>
                                                </div>
                                                <div class="align-self-center text-center analytics-icon" style="font-size: 70px;">
                                                    <em class="icon ni ni-notice text-info"></em>
                                                </div>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 mb-3">
                                <div class="card">
                                    <div class="card-body p-0">
                                        <div class="media p-3">
                                            <a href="{{ route('storage_unit.index') }}" class="media-body" style="display: contents;">
                                                <div class="media-body">
                                                    <h6 class="title">Occupancy</h6>
                                                    <h3 class="mb-0 mt-2">{{ number_format($data['occupancy_percent'] ?? 0, 1) }}%</h3>
                                                </div>
                                                <div class="align-self-center text-center analytics-icon" style="font-size: 70px;">
                                                    <em class="icon ni ni-home-alt text-info"></em>
                                                </div>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row g-gs">
                    {{-- Statistics list --}}
                    <div class="col-lg-6">
                        <div class="card h-100">
                            <div class="card-inner">
                                <div class="card-title-group mb-2">
                                    <div class="card-title">
                                        <h6 class="title">Statistics</h6>
                                    </div>
                                </div>
                                <ul class="nk-store-statistics">
                                    <li class="item">
                                        <a href="{{ route('storage_unit.index') }}">
                                            <div class="info">
                                                <div class="title">Total Storage Units</div>
                                                <div class="count">{{ $data['storageunits'] ?: 0 }}</div>
                                            </div>
                                        </a>
                                        <a href="{{ route('storage_unit.index') }}"><em class="icon bg-primary-dim ni ni-grid-alt-fill"></em></a>
                                    </li>
                                    <li class="item">
                                        <a href="{{ route('product.index') }}">
                                            <div class="info">
                                                <div class="title">Total Products</div>
                                                <div class="count">{{ $data['products'] ?: 0 }}</div>
                                            </div>
                                        </a>
                                        <a href="{{ route('product.index') }}"><em class="icon bg-info-dim ni ni-package"></em></a>
                                    </li>
                                    <li class="item">
                                        <a href="{{ route('addon.index') }}">
                                            <div class="info">
                                                <div class="title">Total Addons</div>
                                                <div class="count">{{ $data['addons'] ?: 0 }}</div>
                                            </div>
                                        </a>
                                        <a href="{{ route('addon.index') }}"><em class="icon bg-pink-dim ni ni-plus-circle"></em></a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    {{-- Sales Statistics --}}
                    <div class="col-lg-6">
                        <div class="card card-full">
                            <div class="nk-ecwg nk-ecwg8 h-100">
                                <div class="card-inner">
                                    <div class="card-title-group mb-3">
                                        <div class="card-title">
                                            <h6 class="title">Sales Statistics</h6>
                                        </div>
                                        <div class="card-tools">
                                            <div class="dropdown">
                                                <a href="#" id="salesPeriodToggle"
                                                   class="dropdown-toggle link link-light link-sm dropdown-indicator"
                                                   data-bs-toggle="dropdown">Weekly</a>
                                                <div class="dropdown-menu dropdown-menu-sm dropdown-menu-end">
                                                    <ul class="link-list-opt no-bdr" id="salesPeriodMenu">
                                                        <li><a href="#" data-period="daily"><span>Daily</span></a></li>
                                                        <li><a href="#" data-period="weekly" class="active"><span>Weekly</span></a></li>
                                                        <li><a href="#" data-period="monthly"><span>Monthly</span></a></li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <ul class="nk-ecwg8-legends">
                                        <li>
                                            <div class="title">
                                                <span class="dot dot-lg sq" style="background:#9d72ff;"></span>
                                                <span>Collected</span>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="title">
                                                <span class="dot dot-lg sq" style="background:#eb6459;"></span>
                                                <span>Invoiced</span>
                                            </div>
                                        </li>
                                    </ul>
                                    <div class="nk-ecwg8-ck">
                                        <canvas id="salesStatisticsChart"></canvas>
                                    </div>
                                    <div class="chart-label-group ps-5">
                                        <div class="chart-label" id="salesStartLabel">{{ $data['sales']['start_label'] ?? '' }}</div>
                                        <div class="chart-label" id="salesEndLabel">{{ $data['sales']['end_label'] ?? '' }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Lead Statistics --}}
                    <div class="col-lg-4">
                        <div class="card card-full overflow-hidden">
                            <div class="nk-ecwg nk-ecwg7 h-100">
                                <div class="card-inner flex-grow-1">
                                    <div class="card-title-group mb-4">
                                        <div class="card-title">
                                            <h6 class="title">Lead Statistics</h6>
                                        </div>
                                    </div>
                                    <div class="nk-ecwg7-ck">
                                        <canvas id="leadStatisticsChart" width="180" height="180"></canvas>
                                    </div>
                                    <ul class="nk-ecwg7-legends" id="leadStatisticsLegend">
                                        @foreach(($data['lead_stats']['labels'] ?? []) as $i => $label)
                                            <li>
                                                <div class="title">
                                                    <span class="dot dot-lg sq" style="background:{{ $data['lead_stats']['background'][$i] ?? '#816bff' }};"></span>
                                                    <span>{{ $label }} ({{ $data['lead_stats']['data'][$i] ?? 0 }})</span>
                                                </div>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Storage Unit Statistics --}}
                    <div class="col-lg-4">
                        <div class="card card-full overflow-hidden">
                            <div class="nk-ecwg nk-ecwg7 h-100">
                                <div class="card-inner flex-grow-1">
                                    <div class="card-title-group mb-4">
                                        <div class="card-title">
                                            <h6 class="title">Storage Unit Status</h6>
                                        </div>
                                    </div>
                                    <div class="nk-ecwg7-ck">
                                        <canvas id="storageStatisticsChart" width="180" height="180"></canvas>
                                    </div>
                                    <ul class="nk-ecwg7-legends">
                                        @foreach(($data['storage_stats']['labels'] ?? []) as $i => $label)
                                            <li>
                                                <div class="title">
                                                    <span class="dot dot-lg sq" style="background:{{ $data['storage_stats']['background'][$i] ?? '#816bff' }};"></span>
                                                    <span>{{ $label }} ({{ $data['storage_stats']['data'][$i] ?? 0 }})</span>
                                                </div>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Invoice Payment Statistics --}}
                    <div class="col-lg-4">
                        <div class="card card-full overflow-hidden">
                            <div class="nk-ecwg nk-ecwg7 h-100">
                                <div class="card-inner flex-grow-1">
                                    <div class="card-title-group mb-4">
                                        <div class="card-title">
                                            <h6 class="title">Invoice Payment Status</h6>
                                        </div>
                                    </div>
                                    <div class="nk-ecwg7-ck">
                                        <canvas id="invoiceStatisticsChart" width="180" height="180"></canvas>
                                    </div>
                                    <ul class="nk-ecwg7-legends">
                                        @foreach(($data['invoice_stats']['labels'] ?? []) as $i => $label)
                                            <li>
                                                <div class="title">
                                                    <span class="dot dot-lg sq" style="background:{{ $data['invoice_stats']['background'][$i] ?? '#816bff' }};"></span>
                                                    <span>{{ $label }} ({{ $data['invoice_stats']['data'][$i] ?? 0 }})</span>
                                                </div>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Recent Orders --}}
                    <div class="col-lg-8">
                        <div class="card card-full">
                            <div class="card-inner">
                                <div class="card-title-group">
                                    <div class="card-title">
                                        <h6 class="title">Recent Orders</h6>
                                    </div>
                                    <div class="card-tools">
                                        <a href="{{ route('order.index') }}" class="link link-primary">View All</a>
                                    </div>
                                </div>
                            </div>
                            <div class="nk-tb-list mt-n2">
                                <div class="nk-tb-item nk-tb-head">
                                    <div class="nk-tb-col"><span>Order No.</span></div>
                                    <div class="nk-tb-col tb-col-sm"><span>Customer</span></div>
                                    <div class="nk-tb-col tb-col-md"><span>Date</span></div>
                                    <div class="nk-tb-col"><span>Amount</span></div>
                                    <div class="nk-tb-col tb-col-md"><span>Status</span></div>
                                </div>
                                @forelse(($data['orders'] ?? []) as $order)
                                    @php
                                        $contact = optional($order->customer)->primaryContact;
                                        $firstName = $contact->first_name ?? '';
                                        $lastName = $contact->last_name ?? '';
                                        $initials = strtoupper(mb_substr($firstName, 0, 1) . mb_substr($lastName, 0, 1));
                                        $customerName = optional($order->customer)->full_display_name
                                            ?: trim($firstName . ' ' . $lastName)
                                            ?: 'N/A';
                                        $isActive = ((int) $order->getRawOriginal('status') === 1);
                                        $orderDate = $order->created_at
                                            ? \Carbon\Carbon::parse($order->getRawOriginal('created_at'))->format('d M, Y')
                                            : '—';
                                    @endphp
                                    <div class="nk-tb-item">
                                        <div class="nk-tb-col">
                                            <span class="tb-lead">
                                                <a href="{{ url('admin/edit-order/' . $order->id) }}">#{{ $order->id }}</a>
                                            </span>
                                        </div>
                                        <div class="nk-tb-col tb-col-sm">
                                            <div class="user-card">
                                                <div class="user-avatar sm bg-purple-dim">
                                                    <span>{{ $initials ?: '?' }}</span>
                                                </div>
                                                <div class="user-name">
                                                    <span class="tb-lead">{{ $customerName }}</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="nk-tb-col tb-col-md">
                                            <span class="tb-sub">{{ $orderDate }}</span>
                                        </div>
                                        <div class="nk-tb-col">
                                            <span class="tb-sub tb-amount">{{ number_format((float) $order->sub_amount, 2) }} <span>AED</span></span>
                                        </div>
                                        <div class="nk-tb-col tb-col-md">
                                            <span class="badge badge-dim {{ $isActive ? 'badge-success' : 'badge-secondary' }}">
                                                {{ $isActive ? 'Active' : 'In-Active' }}
                                            </span>
                                        </div>
                                    </div>
                                @empty
                                    <div class="nk-tb-item">
                                        <div class="nk-tb-col">
                                            <span class="tb-sub text-soft">No recent orders.</span>
                                        </div>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>

                    {{-- Contracts ending soon --}}
                    <div class="col-lg-4">
                        <div class="card card-full">
                            <div class="card-inner">
                                <div class="card-title-group">
                                    <div class="card-title">
                                        <h6 class="title">Contracts Ending (30 days)</h6>
                                    </div>
                                    <div class="card-tools">
                                        <a href="{{ route('contract.index') }}" class="link link-primary">View All</a>
                                    </div>
                                </div>
                            </div>
                            <div class="nk-tb-list mt-n2">
                                <div class="nk-tb-item nk-tb-head">
                                    <div class="nk-tb-col"><span>Contract</span></div>
                                    <div class="nk-tb-col"><span>End Date</span></div>
                                </div>
                                @forelse(($data['contracts_ending'] ?? []) as $contract)
                                    @php
                                        $customerName = optional($contract->customer)->full_display_name ?: 'N/A';
                                        try {
                                            $endDateLabel = \Carbon\Carbon::parse($contract->end_date)->format('d M, Y');
                                        } catch (\Throwable $e) {
                                            $endDateLabel = $contract->end_date;
                                        }
                                        $title = $contract->subject ?: ('#' . $contract->id);
                                    @endphp
                                    <div class="nk-tb-item">
                                        <div class="nk-tb-col">
                                            <span class="tb-lead">{{ $title }}</span>
                                            <span class="tb-sub">{{ $customerName }}</span>
                                        </div>
                                        <div class="nk-tb-col">
                                            <span class="tb-sub">{{ $endDateLabel }}</span>
                                        </div>
                                    </div>
                                @empty
                                    <div class="nk-tb-item">
                                        <div class="nk-tb-col">
                                            <span class="tb-sub text-soft">No contracts ending soon.</span>
                                        </div>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>

                    {{-- Recent Invoices --}}
                    <div class="col-lg-12">
                        <div class="card card-full">
                            <div class="card-inner">
                                <div class="card-title-group">
                                    <div class="card-title">
                                        <h6 class="title">Recent Invoices</h6>
                                    </div>
                                    <div class="card-tools">
                                        <a href="{{ route('invoice.index') }}" class="link link-primary">View All</a>
                                    </div>
                                </div>
                            </div>
                            <div class="nk-tb-list mt-n2">
                                <div class="nk-tb-item nk-tb-head">
                                    <div class="nk-tb-col"><span>Invoice</span></div>
                                    <div class="nk-tb-col tb-col-sm"><span>Customer</span></div>
                                    <div class="nk-tb-col tb-col-md"><span>Due Date</span></div>
                                    <div class="nk-tb-col"><span>Amount</span></div>
                                    <div class="nk-tb-col tb-col-md"><span>Status</span></div>
                                </div>
                                @forelse(($data['recent_invoices'] ?? []) as $invoice)
                                    @php
                                        $customerName = optional($invoice->customer)->full_display_name ?: 'N/A';
                                        $contact = optional($invoice->customer)->primaryContact;
                                        $firstName = $contact->first_name ?? '';
                                        $lastName = $contact->last_name ?? '';
                                        $initials = strtoupper(mb_substr($firstName, 0, 1) . mb_substr($lastName, 0, 1));
                                        $paymentStatus = $invoice->resolvePaymentStatusValue();
                                        $statusLabel = match ($paymentStatus) {
                                            \App\Models\Invoice::PAYMENT_PAID => 'Paid',
                                            \App\Models\Invoice::PAYMENT_PARTIAL => 'Partial',
                                            default => 'Pending',
                                        };
                                        $badgeClass = match ($paymentStatus) {
                                            \App\Models\Invoice::PAYMENT_PAID => 'badge-success',
                                            \App\Models\Invoice::PAYMENT_PARTIAL => 'badge-warning',
                                            default => 'badge-danger',
                                        };
                                        try {
                                            $dueLabel = $invoice->due_date
                                                ? \Carbon\Carbon::parse($invoice->due_date)->format('d M, Y')
                                                : '—';
                                        } catch (\Throwable $e) {
                                            $dueLabel = $invoice->due_date ?: '—';
                                        }
                                    @endphp
                                    <div class="nk-tb-item">
                                        <div class="nk-tb-col">
                                            <span class="tb-lead">
                                                <a href="{{ route('detail-invoice', $invoice->id) }}">{{ $invoice->invoice_no ?: ('#' . $invoice->id) }}</a>
                                            </span>
                                        </div>
                                        <div class="nk-tb-col tb-col-sm">
                                            <div class="user-card">
                                                <div class="user-avatar sm bg-purple-dim">
                                                    <span>{{ $initials ?: '?' }}</span>
                                                </div>
                                                <div class="user-name">
                                                    <span class="tb-lead">{{ $customerName }}</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="nk-tb-col tb-col-md">
                                            <span class="tb-sub">{{ $dueLabel }}</span>
                                        </div>
                                        <div class="nk-tb-col">
                                            <span class="tb-sub tb-amount">{{ number_format((float) $invoice->grand_total, 2) }} <span>AED</span></span>
                                        </div>
                                        <div class="nk-tb-col tb-col-md">
                                            <span class="badge badge-dim {{ $badgeClass }}">{{ $statusLabel }}</span>
                                        </div>
                                    </div>
                                @empty
                                    <div class="nk-tb-item">
                                        <div class="nk-tb-col">
                                            <span class="tb-sub text-soft">No recent invoices.</span>
                                        </div>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endisset
    </div>
@endsection

@push('scripts')
<script>
(function ($) {
    'use strict';

    var salesUrl = @json(route('admin.sales-stat'));
    var initialSales = @json($data['sales'] ?? []);
    var leadStats = @json($data['lead_stats'] ?? ['labels' => [], 'data' => [], 'background' => []]);
    var storageStats = @json($data['storage_stats'] ?? ['labels' => [], 'data' => [], 'background' => []]);
    var invoiceStats = @json($data['invoice_stats'] ?? ['labels' => [], 'data' => [], 'background' => []]);

    var salesChart = null;
    var periodLabels = { daily: 'Daily', weekly: 'Weekly', monthly: 'Monthly' };

    function hexRgb(hex, alpha) {
        var h = (hex || '#9d72ff').replace('#', '');
        if (h.length === 3) {
            h = h[0] + h[0] + h[1] + h[1] + h[2] + h[2];
        }
        var r = parseInt(h.substring(0, 2), 16);
        var g = parseInt(h.substring(2, 4), 16);
        var b = parseInt(h.substring(4, 6), 16);
        return 'rgba(' + r + ',' + g + ',' + b + ',' + (alpha || 0.15) + ')';
    }

    function renderSalesChart(payload) {
        var canvas = document.getElementById('salesStatisticsChart');
        if (!canvas || typeof Chart === 'undefined') {
            return;
        }

        var ctx = canvas.getContext('2d');
        var step = payload.step_size || 100;

        if (salesChart) {
            salesChart.destroy();
        }

        salesChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: payload.labels || [],
                datasets: [
                    {
                        label: 'Collected',
                        tension: 0.4,
                        backgroundColor: hexRgb('#9d72ff', 0.15),
                        borderWidth: 2,
                        borderDash: [],
                        borderColor: '#9d72ff',
                        pointBorderColor: 'transparent',
                        pointBackgroundColor: 'transparent',
                        pointHoverBackgroundColor: '#fff',
                        pointHoverBorderColor: '#9d72ff',
                        pointBorderWidth: 2,
                        pointHoverRadius: 4,
                        pointHoverBorderWidth: 2,
                        pointRadius: 4,
                        pointHitRadius: 4,
                        data: payload.collected || []
                    },
                    {
                        label: 'Invoiced',
                        tension: 0.4,
                        backgroundColor: 'transparent',
                        borderWidth: 2,
                        borderDash: [5],
                        borderColor: '#eb6459',
                        pointBorderColor: 'transparent',
                        pointBackgroundColor: 'transparent',
                        pointHoverBackgroundColor: '#fff',
                        pointHoverBorderColor: '#eb6459',
                        pointBorderWidth: 2,
                        pointHoverRadius: 4,
                        pointHoverBorderWidth: 2,
                        pointRadius: 4,
                        pointHitRadius: 4,
                        data: payload.invoiced || []
                    }
                ]
            },
            options: {
                legend: { display: false },
                maintainAspectRatio: false,
                tooltips: {
                    enabled: true,
                    callbacks: {
                        label: function (tooltipItem, data) {
                            var label = data.datasets[tooltipItem.datasetIndex].label || '';
                            return label + ': AED ' + tooltipItem.yLabel;
                        }
                    },
                    backgroundColor: '#1c2b46',
                    titleFontSize: 13,
                    titleFontColor: '#fff',
                    bodyFontColor: '#fff',
                    bodyFontSize: 12,
                    displayColors: false
                },
                scales: {
                    yAxes: [{
                        display: true,
                        ticks: {
                            beginAtZero: true,
                            fontSize: 11,
                            fontColor: '#9eaecf',
                            padding: 10,
                            stepSize: step,
                            callback: function (value) {
                                return 'AED ' + value;
                            }
                        },
                        gridLines: {
                            color: 'rgba(82,100,132,0.2)',
                            tickMarkLength: 0,
                            zeroLineColor: 'rgba(82,100,132,0.2)'
                        }
                    }],
                    xAxes: [{
                        display: false,
                        ticks: {
                            fontSize: 9,
                            fontColor: '#9eaecf',
                            source: 'auto',
                            padding: 10
                        },
                        gridLines: {
                            color: 'transparent',
                            tickMarkLength: 0,
                            zeroLineColor: 'transparent'
                        }
                    }]
                }
            }
        });

        $('#salesStartLabel').text(payload.start_label || '');
        $('#salesEndLabel').text(payload.end_label || '');
    }

    function renderDoughnut(canvasId, stats) {
        var canvas = document.getElementById(canvasId);
        if (!canvas || typeof Chart === 'undefined') {
            return;
        }

        var labels = (stats.labels || []).slice();
        var background = (stats.background || ['#816bff']).slice();
        var data = (stats.data || [0]).slice();
        var total = data.reduce(function (sum, value) { return sum + (Number(value) || 0); }, 0);

        // Chart.js hides rings when every slice is 0 — keep a single theme-colored ring.
        if (total === 0) {
            labels = [labels[0] || 'None'];
            background = [background[0] || '#816bff'];
            data = [1];
        }

        new Chart(canvas.getContext('2d'), {
            type: 'doughnut',
            data: {
                labels: labels,
                datasets: [{
                    backgroundColor: background,
                    borderWidth: 2,
                    borderColor: '#fff',
                    hoverBorderColor: '#fff',
                    data: data
                }]
            },
            options: {
                legend: { display: false },
                rotation: -1.5,
                cutoutPercentage: 70,
                maintainAspectRatio: false,
                tooltips: {
                    enabled: true,
                    callbacks: {
                        label: function (tooltipItem, data) {
                            var label = data.labels[tooltipItem.index] || '';
                            var value = data.datasets[0].data[tooltipItem.index] || 0;
                            return label + ': ' + value;
                        }
                    },
                    backgroundColor: '#1c2b46',
                    titleFontSize: 13,
                    titleFontColor: '#fff',
                    bodyFontColor: '#fff',
                    bodyFontSize: 12,
                    displayColors: false
                }
            }
        });
    }

    function loadSalesPeriod(period) {
        $.ajax({
            url: salesUrl,
            type: 'GET',
            dataType: 'json',
            data: { period: period },
            success: function (payload) {
                renderSalesChart(payload);
                $('#salesPeriodToggle').text(periodLabels[period] || 'Weekly');
                $('#salesPeriodMenu a').removeClass('active');
                $('#salesPeriodMenu a[data-period="' + period + '"]').addClass('active');
            },
            error: function () {
                if (typeof toastr !== 'undefined') {
                    toastr.error('Unable to load sales statistics');
                }
            }
        });
    }

    $(function () {
        renderSalesChart(initialSales);
        renderDoughnut('leadStatisticsChart', leadStats);
        renderDoughnut('storageStatisticsChart', storageStats);
        renderDoughnut('invoiceStatisticsChart', invoiceStats);

        $('#salesPeriodMenu').on('click', 'a[data-period]', function (e) {
            e.preventDefault();
            var period = $(this).data('period');
            loadSalesPeriod(period);
        });
    });
})(jQuery);
</script>
@endpush
