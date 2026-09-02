@extends('ui.layouts.frontend')
@section('title', '| Customer Dashboard')
@section('metaTitle', 'Customer Dashboard - StorageKeys')
@section('metaDescription', 'Manage your StorageKeys account — estimates, contracts, invoices, orders and profile details.')
@section('robots', 'noindex, nofollow')

@section('content')
@php
    $user = Auth::user();
    $customer = optional($user->customer);
    $fullName = trim(($user->first_name ?? '') . ' ' . ($user->last_name ?? ''));
@endphp

<div class="sk-home">

    <section class="ps-hero">
        <div class="sk-container">
            <div class="ps-crumb">
                <a href="{{ url('/') }}">Home</a>
                <i class="fas fa-chevron-right" aria-hidden="true"></i>
                <span>My Account</span>
            </div>
            <span class="sk-eyebrow" style="color:#ffcf9e;">Customer Account</span>
            <h1>Welcome back{{ $fullName ? ', ' . e($fullName) : '' }}</h1>
            <p class="lead">Track estimates, contracts, invoices and orders — and keep your account details up to date.</p>
        </div>
    </section>

    <section class="sk-section" style="background:var(--sk-soft);">
        <div class="sk-container">
            @if (session()->has('success'))
                <div class="ca-alert ca-alert-success">
                    @if(is_array(session('success')))
                        <ul>
                            @foreach (session('success') as $message)
                                <li>{{ $message }}</li>
                            @endforeach
                        </ul>
                    @else
                        {{ session('success') }}
                    @endif
                </div>
            @endif
            @if ($errors->any())
                <div class="ca-alert ca-alert-error">
                    <strong>Please check the following:</strong>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="cd-layout">
                <aside class="cd-sidebar">
                    <nav class="cd-nav" aria-label="Account sections">
                        <button type="button" class="cd-nav-btn is-active" data-cd-tab="dashboard">
                            <i class="fas fa-home" aria-hidden="true"></i> Dashboard
                        </button>
                        <button type="button" class="cd-nav-btn" data-cd-tab="estimates">
                            <i class="fas fa-file-alt" aria-hidden="true"></i> Estimates
                            <span class="cd-count">{{ (int) ($data['estimateCount'] ?? 0) }}</span>
                        </button>
                        <button type="button" class="cd-nav-btn" data-cd-tab="contracts">
                            <i class="fas fa-file-signature" aria-hidden="true"></i> Contracts
                            <span class="cd-count">{{ (int) ($data['contractCount'] ?? 0) }}</span>
                        </button>
                        <button type="button" class="cd-nav-btn" data-cd-tab="invoices">
                            <i class="fas fa-file-invoice" aria-hidden="true"></i> Invoices
                            <span class="cd-count">{{ (int) ($data['invoiceCount'] ?? 0) }}</span>
                        </button>
                        <button type="button" class="cd-nav-btn" data-cd-tab="orders">
                            <i class="fas fa-shopping-bag" aria-hidden="true"></i> Orders
                            <span class="cd-count">{{ (int) ($data['orderCount'] ?? 0) }}</span>
                        </button>
                        <button type="button" class="cd-nav-btn" data-cd-tab="account">
                            <i class="fas fa-user" aria-hidden="true"></i> Account Details
                        </button>
                        <a href="#" class="cd-nav-btn cd-nav-logout" onclick="event.preventDefault(); document.getElementById('customer-logout-form').submit();">
                            <i class="fas fa-sign-out-alt" aria-hidden="true"></i> Logout
                        </a>
                    </nav>
                    <form id="customer-logout-form" action="{{ route('all.logout') }}" method="POST" class="d-none">@csrf</form>
                </aside>

                <div class="cd-main">
                    {{-- Dashboard --}}
                    <div class="cd-panel is-active" id="cd-panel-dashboard" role="tabpanel">
                        <div class="cd-panel-head">
                            <h2>Dashboard</h2>
                            <p>Hello <strong>{{ $fullName ?: 'there' }}</strong> — here is a quick overview of your account.</p>
                        </div>
                        <div class="cd-stats">
                            <button type="button" class="cd-stat" data-cd-goto="estimates">
                                <span class="cd-stat-label"><i class="fas fa-file-alt" aria-hidden="true"></i> Estimates</span>
                                <span class="cd-stat-value">{{ (int) ($data['estimateCount'] ?? 0) }}</span>
                            </button>
                            <button type="button" class="cd-stat" data-cd-goto="contracts">
                                <span class="cd-stat-label"><i class="fas fa-file-signature" aria-hidden="true"></i> Contracts</span>
                                <span class="cd-stat-value">{{ (int) ($data['contractCount'] ?? 0) }}</span>
                            </button>
                            <button type="button" class="cd-stat" data-cd-goto="invoices">
                                <span class="cd-stat-label"><i class="fas fa-file-invoice" aria-hidden="true"></i> Invoices</span>
                                <span class="cd-stat-value">{{ (int) ($data['invoiceCount'] ?? 0) }}</span>
                            </button>
                            <button type="button" class="cd-stat" data-cd-goto="orders">
                                <span class="cd-stat-label"><i class="fas fa-shopping-bag" aria-hidden="true"></i> Orders</span>
                                <span class="cd-stat-value">{{ (int) ($data['orderCount'] ?? 0) }}</span>
                            </button>
                        </div>
                    </div>

                    {{-- Estimates --}}
                    <div class="cd-panel" id="cd-panel-estimates" role="tabpanel" hidden>
                        <div class="cd-panel-head">
                            <h2>Estimates</h2>
                            <p>View storage estimates prepared for your account.</p>
                        </div>
                        @if(isset($data['estimate']) && $data['estimate']->count())
                            <div class="cd-table-wrap">
                                <table class="cd-table">
                                    <thead>
                                        <tr>
                                            <th>Estimate</th>
                                            <th>Date</th>
                                            <th>Status</th>
                                            <th>Unit Price</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($data['estimate'] as $estimate)
                                            <tr>
                                                <td>
                                                    @php
                                                        $names = ($estimate->estimateStorageUnits ?? collect())->map(function($u) {
                                                            return optional($u->storageunit)->storage_unit_name;
                                                        })->filter()->values();
                                                        if ($names->isEmpty()) {
                                                            $names = collect([optional($estimate->storageunit)->storage_unit_name])->filter();
                                                        }
                                                    @endphp
                                                    {{ $names->implode(', ') }} / {{ optional($estimate->termLength)->title }}
                                                </td>
                                                <td>{{ $estimate->estimate_date }}</td>
                                                <td><span class="cd-badge">{{ $estimate->status }}</span></td>
                                                <td>AED {{ $estimate->unit_price }}</td>
                                                <td><a class="cd-link" href="{{ url('/estimatetocustomer') . '/' . hashid_encode($estimate->id) }}">View</a></td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="cd-empty">
                                <i class="fas fa-file-alt" aria-hidden="true"></i>
                                <p>No estimates yet.</p>
                            </div>
                        @endif
                    </div>

                    {{-- Contracts --}}
                    <div class="cd-panel" id="cd-panel-contracts" role="tabpanel" hidden>
                        <div class="cd-panel-head">
                            <h2>Contracts</h2>
                            <p>Sign or download your storage contracts.</p>
                        </div>
                        @if(isset($data['contract']) && $data['contract']->count())
                            <div class="cd-table-wrap">
                                <table class="cd-table">
                                    <thead>
                                        <tr>
                                            <th>Contract</th>
                                            <th>Date</th>
                                            <th>Expire</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($data['contract'] as $contract)
                                            <tr>
                                                <td>{{ $contract->subject }}</td>
                                                <td>{{ $contract->start_date }}</td>
                                                <td>{{ $contract->end_date }}</td>
                                                <td><span class="cd-badge">{{ $contract->is_signed }}</span></td>
                                                <td>
                                                    @if($contract->is_signed == 'Signed')
                                                        <a class="cd-link" href="{{ url('customer/contract-pdf') . '/' . hashid_encode($contract->id) }}">Download</a>
                                                    @else
                                                        <a class="cd-link" href="{{ url('customer/contract-to-customer') . '/' . hashid_encode($contract->id) }}"><i class="fas fa-pen" aria-hidden="true"></i> Sign Contract</a>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="cd-empty">
                                <i class="fas fa-file-signature" aria-hidden="true"></i>
                                <p>No contracts yet.</p>
                            </div>
                        @endif
                    </div>

                    {{-- Invoices --}}
                    <div class="cd-panel" id="cd-panel-invoices" role="tabpanel" hidden>
                        <div class="cd-panel-head">
                            <h2>Invoices</h2>
                            <p>Download or view invoices linked to your contracts and orders.</p>
                        </div>
                        @if(isset($data['invoice']) && $data['invoice']->count())
                            <div class="cd-table-wrap">
                                <table class="cd-table">
                                    <thead>
                                        <tr>
                                            <th>Contract / Order</th>
                                            <th>Invoice</th>
                                            <th>Date</th>
                                            <th>Total</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($data['invoice'] as $invoice)
                                            <tr>
                                                <td>
                                                    @if($invoice->type == 'contract')
                                                        {{ !empty($invoice->contract->subject) ? $invoice->contract->subject : '—' }}
                                                    @else
                                                        {{ !empty($invoice->order) ? 'Order No#-' . $invoice->order->id : '—' }}
                                                    @endif
                                                </td>
                                                <td>{{ $invoice->invoice_no }}</td>
                                                <td>{{ $invoice->invoice_date }}</td>
                                                <td>AED {{ $invoice->grand_total }}</td>
                                                <td><span class="cd-badge {{ $invoice->paymentStatusBadgeClass() }}">{{ $invoice->payment_status }}</span></td>
                                                <td class="cd-actions">
                                                    <a class="cd-link" href="{{ url('customer/pdf-invoice') . '/' . hashid_encode($invoice->id) }}">Download</a>
                                                    <a class="cd-link" href="{{ url('customer/invoice-to-customer') . '/' . hashid_encode($invoice->id) }}">View</a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="cd-empty">
                                <i class="fas fa-file-invoice" aria-hidden="true"></i>
                                <p>No invoices yet.</p>
                            </div>
                        @endif
                    </div>

                    {{-- Orders --}}
                    <div class="cd-panel" id="cd-panel-orders" role="tabpanel" hidden>
                        <div class="cd-panel-head">
                            <h2>Orders</h2>
                            <p>Shop and packing supply orders from your account.</p>
                        </div>
                        @if(isset($data['order']) && $data['order']->count())
                            <div class="cd-table-wrap">
                                <table class="cd-table">
                                    <thead>
                                        <tr>
                                            <th>Order#</th>
                                            <th>Payment Method</th>
                                            <th>Date</th>
                                            <th>Total Amount</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($data['order'] as $order)
                                            <tr>
                                                <td>{{ 'Order No#-' . $order->id }}</td>
                                                <td>{{ $order->payment_method }}</td>
                                                <td>{{ $order->created_at }}</td>
                                                <td>AED {{ $order->sub_amount }}</td>
                                                <td><span class="cd-badge">{{ $order->status }}</span></td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="cd-empty">
                                <i class="fas fa-shopping-bag" aria-hidden="true"></i>
                                <p>No orders yet.</p>
                                <a href="{{ url('/shop') }}" class="sk-btn sk-btn-outline">Browse Shop</a>
                            </div>
                        @endif
                    </div>

                    {{-- Account Details --}}
                    <div class="cd-panel" id="cd-panel-account" role="tabpanel" hidden>
                        <div class="cd-panel-head">
                            <h2>Account Details</h2>
                            <p>Update your profile, billing address and password.</p>
                        </div>
                        <form action="{{ route('customer.update-profile') }}" method="POST" class="ca-auth-form cd-account-form">
                            @csrf
                            <div class="ca-field-row">
                                <div class="ca-field">
                                    <label for="cd-first-name">First name</label>
                                    <input id="cd-first-name" type="text" name="first_name" value="{{ old('first_name', $user->first_name) }}" required>
                                </div>
                                <div class="ca-field">
                                    <label for="cd-last-name">Last name</label>
                                    <input id="cd-last-name" type="text" name="last_name" value="{{ old('last_name', $user->last_name) }}" required>
                                </div>
                            </div>
                            <div class="ca-field">
                                <label for="cd-email">Email</label>
                                <input id="cd-email" type="email" name="email" value="{{ old('email', $user->email) }}" required>
                            </div>

                            <fieldset class="cd-fieldset">
                                <legend>Billing Address</legend>
                                <div class="ca-field">
                                    <label for="cd-address">Address</label>
                                    <input id="cd-address" type="text" name="address" value="{{ old('address', $customer->address) }}">
                                </div>
                                <div class="ca-field-row">
                                    <div class="ca-field">
                                        <label for="cd-city">City</label>
                                        <input id="cd-city" type="text" name="city" value="{{ old('city', $customer->city) }}">
                                    </div>
                                    <div class="ca-field">
                                        <label for="cd-country">Country</label>
                                        <input id="cd-country" type="text" name="country" value="{{ old('country', $customer->country) }}">
                                    </div>
                                </div>
                            </fieldset>

                            <fieldset class="cd-fieldset">
                                <legend>Password change</legend>
                                <p class="cd-hint">Leave blank if you do not want to change your password.</p>
                                <div class="ca-field">
                                    <label for="cd-current-password">Current password</label>
                                    <div class="password-field-wrap">
                                        <input id="cd-current-password" type="password" name="current_password" autocomplete="current-password">
                                        <button type="button" class="password-toggle" aria-label="Show password">
                                            <i class="far fa-eye" aria-hidden="true"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="ca-field-row">
                                    <div class="ca-field">
                                        <label for="cd-password">New password</label>
                                        <div class="password-field-wrap">
                                            <input id="cd-password" type="password" name="password" autocomplete="new-password">
                                            <button type="button" class="password-toggle" aria-label="Show password">
                                                <i class="far fa-eye" aria-hidden="true"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="ca-field">
                                        <label for="cd-password-confirm">Confirm new password</label>
                                        <div class="password-field-wrap">
                                            <input id="cd-password-confirm" type="password" name="password_confirmation" autocomplete="new-password">
                                            <button type="button" class="password-toggle" aria-label="Show password">
                                                <i class="far fa-eye" aria-hidden="true"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </fieldset>

                            <button type="submit" class="sk-btn sk-btn-primary">
                                <i class="fas fa-save" aria-hidden="true"></i> Save Changes
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection

@section('javascriptWork')
<script>
(function () {
    var buttons = document.querySelectorAll('[data-cd-tab]');
    var panels = document.querySelectorAll('.cd-panel');
    var gotoBtns = document.querySelectorAll('[data-cd-goto]');

    function showTab(name) {
        buttons.forEach(function (btn) {
            btn.classList.toggle('is-active', btn.getAttribute('data-cd-tab') === name);
        });
        panels.forEach(function (panel) {
            var match = panel.id === 'cd-panel-' + name;
            panel.classList.toggle('is-active', match);
            if (match) {
                panel.removeAttribute('hidden');
            } else {
                panel.setAttribute('hidden', '');
            }
        });
        try {
            history.replaceState(null, '', '#cd-' + name);
        } catch (e) {}
    }

    buttons.forEach(function (btn) {
        btn.addEventListener('click', function () {
            showTab(btn.getAttribute('data-cd-tab'));
        });
    });

    gotoBtns.forEach(function (btn) {
        btn.addEventListener('click', function () {
            showTab(btn.getAttribute('data-cd-goto'));
        });
    });

    var hash = (window.location.hash || '').replace('#cd-', '');
    if (hash && document.getElementById('cd-panel-' + hash)) {
        showTab(hash);
    }
})();
</script>
@endsection
