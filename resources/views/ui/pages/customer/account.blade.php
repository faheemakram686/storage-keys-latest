@extends('ui.layouts.frontend2')
@section('title', '| Account')
@section('content')


    <div class="ltn__utilize-overlay"></div>

    <!-- BREADCRUMB AREA START -->
    <div class="ltn__breadcrumb-area text-left bg-overlay-white-30 bg-image "   data-bg="{{ asset('sk-assets/assets/images/frontend/bg/Inner_Small_Banner_3.jpg') }}">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="ltn__breadcrumb-inner">
                        <h1 class="page-title">My Account</h1>
                        <div class="ltn__breadcrumb-list">
                            <ul>
                                <li><a href="{{ url('/') }}"><span class="ltn__secondary-color"><i class="fas fa-home"></i></span> Home</a></li>
                                <li>My Account</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- BREADCRUMB AREA END -->

    <!-- WISHLIST AREA START -->
    <div class="liton__wishlist-area pb-70">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    @if (session()->has('success'))
                        <div class="alert alert-success">
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
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                    <!-- PRODUCT TAB AREA START -->
                    <div class="ltn__product-tab-area">
                        <div class="container">

                            <div class="row">
                                <div class="col-lg-3">
                                    <div class="ltn__tab-menu-list mb-50">
                                        <div class="nav">
                                            <a class="active show" data-toggle="tab" href="#liton_tab_1_1">Dashboard <i class="fas fa-home"></i></a>
                                            <a data-toggle="tab" href="#liton_tab_1_2">Estimates <i class="fas fa-file-alt"></i></a>
                                            <a data-toggle="tab" href="#liton_tab_1_3">Contracts <i class="fas fa-arrow-down"></i></a>
                                            <a data-toggle="tab" href="#liton_tab_1_4">Invoices <i class="fas fa-arrow-down"></i></a>
                                            <a data-toggle="tab" href="#liton_tab_1_5">Orders <i class="fas fa-map-marker-alt"></i></a>
                                            <a data-toggle="tab" href="#liton_tab_1_6">Account Details <i class="fas fa-user"></i></a>
                                            <a href="#"  onclick="event.preventDefault(); document.getElementById('logout-form').submit();" title="Logout">Logout <i class="fas fa-sign-out-alt"></i></a>
                                            <form id="logout-form" action="{{ route('all.logout') }}" method="POST" class="d-none">@csrf</form>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-9">
                                    <div class="tab-content">
                                        <div class="tab-pane fade active show" id="liton_tab_1_1">
                                            <div class="ltn__myaccount-tab-content-inner">
                                                <p>Hello <strong>{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</strong> (not <strong>UserName</strong>? <small><a href="#"  onclick="event.preventDefault(); document.getElementById('logout-form').submit();" title="Logout">Logout</a></small> )</p>
                                                <div class="row ltn__custom-gutter">
                                                    <div class="col-lg-3 col-sm-6 col-12 m-4">
                                                        <div class="ltn__feature-item ltn__feature-item-6 active">

                                                            <div class="ltn__feature-info">
                                                                <h5><a href="#liton_tab_1_2" data-toggle="tab">Estimates</a></h5>
                                                                <h3 class="mb-0 mt-2">{{($data['estimateCount'])? :"0"}}</h3>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-3 col-sm-6 col-12 m-4">
                                                        <div class="ltn__feature-item ltn__feature-item-6">

                                                            <div class="ltn__feature-info">
                                                                <h5><a href="#liton_tab_1_3" data-toggle="tab">Contracts</a></h5>
                                                                <h3 class="mb-0 mt-2">{{($data['contractCount'])? :"0"}}</h3>

                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-3 col-sm-6 col-12 m-4">
                                                        <div class="ltn__feature-item ltn__feature-item-6">

                                                            <div class="ltn__feature-info">
                                                                <h5><a href="#liton_tab_1_4" data-toggle="tab">Invoices</a></h5>
                                                                <h3 class="mb-0 mt-2">{{($data['invoiceCount'])? :"0"}}</h3>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-3 col-sm-6 col-12 m-4">
                                                        <div class="ltn__feature-item ltn__feature-item-6">

                                                            <div class="ltn__feature-info">
                                                                <h5><a href="#liton_tab_1_5" data-toggle="tab">Orders</a></h5>
                                                                <h3 class="mb-0 mt-2">{{($data['orderCount'])? :"0"}}</h3>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <form id="logout-form" action="{{ route('all.logout') }}" method="POST" class="d-none">@csrf</form>
                                            </div>
                                        </div>
                                        @isset($data['estimate'])
                                        <div class="tab-pane fade" id="liton_tab_1_2">
                                            <div class="ltn__myaccount-tab-content-inner">
                                                <div class="table-responsive">
                                                    <table class="table">
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
                                                                <td>{{$estimate->estimate_date}}</td>
                                                                <td>{{$estimate->status}}</td>
                                                                <td>{{$estimate->unit_price}}</td>
                                                                <td><a href={{url('/estimatetocustomer').'/'.hashid_encode($estimate->id)}}>View</a></td>
                                                            </tr>
                                                        @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                        @endisset
                                        @isset($data['contract'])
                                        <div class="tab-pane fade" id="liton_tab_1_3">
                                            <div class="ltn__myaccount-tab-content-inner">
                                                <div class="table-responsive">
                                                    <table class="table">
                                                        <thead>
                                                        <tr>
                                                            <th>Contract</th>
                                                            <th>Date</th>
                                                            <th>Expire</th>
                                                            <th>Status</th>
                                                            <th>Download</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        @foreach($data['contract'] as $contract)
                                                        <tr>
                                                            <td>{{$contract->subject}}</td>
                                                            <td>{{$contract->start_date}}</td>
                                                            <td>{{$contract->end_date}}</td>
                                                            <td>{{$contract->is_signed}}</td>
                                                            @if($contract->is_signed == 'Signed')
                                                                <td><a href="{{url('customer/contract-pdf').'/'.hashid_encode($contract->id)}}">Download</a></td>
                                                            @else
                                                                <td><a href="{{url('customer/contract-to-customer').'/'.hashid_encode($contract->id)}}"><i class="far fa-arrow-to-bottom mr-1"></i>Sign Contract</a></td>
                                                            @endif
                                                        </tr>
                                                        @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                        @endisset
                                        @isset($data['invoice'])
                                            <div class="tab-pane fade" id="liton_tab_1_4">
                                                <div class="ltn__myaccount-tab-content-inner">
                                                    <div class="table-responsive">
                                                        <table class="table">
                                                            <thead>
                                                            <tr>
                                                                <th>Contract/Order</th>
                                                                <th>Invoice</th>
                                                                <th>Date</th>
                                                                <th>Total Amount</th>
                                                                <th>Status</th>
                                                                <th>Download</th>
                                                            </tr>
                                                            </thead>
                                                            <tbody>
                                                            @foreach($data['invoice'] as $invoice)
                                                                <tr>
                                                                    @if($invoice->type =='contract')
                                                                    <td>{{(! empty($invoice->contract->subject) ? $invoice->contract->subject : '') }}</td>
                                                                    @else
                                                                        <td>{{(! empty($invoice->order) ?  'Order No#-'.$invoice->order->id : '') }}</td>
                                                                        @endif
                                                                    <td>{{$invoice->invoice_no}}</td>
                                                                    <td>{{$invoice->invoice_date}}</td>
                                                                    <td>{{$invoice->grand_total}}</td>
                                                                    <td><span class="badge {{ $invoice->paymentStatusBadgeClass() }}">{{ $invoice->payment_status }}</span></td>
                                                                    <td><a href="{{url('customer/pdf-invoice').'/'.hashid_encode($invoice->id)}}"> Download</a><a href="{{url('customer/invoice-to-customer').'/'.hashid_encode($invoice->id)}}"> View</a></td>
                                                                </tr>
                                                            @endforeach
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        @endisset
                                        @isset($data['order'])
                                            <div class="tab-pane fade" id="liton_tab_1_5">
                                                <div class="ltn__myaccount-tab-content-inner">
                                                    <div class="table-responsive">
                                                        <table class="table">
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
                                                                    <td>{{ 'Order No#-'.$order->id}}</td>
                                                                    <td>{{$order->payment_method}}</td>
                                                                    <td>{{$order->created_at}}</td>
                                                                    <td>{{$order->sub_amount}}</td>
                                                                    <td>{{$order->status}}</td>
{{--                                                                    <td><a href="{{url('customer/pdf-order').'/'.$order->id}}"> Download</a><a href="{{url('customer/invoice-to-customer').'/'.$invoice->id}}"> View</a></td>--}}
                                                                </tr>
                                                            @endforeach
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        @endisset
                                        <div class="tab-pane fade" id="liton_tab_1_5">
                                            <div class="ltn__myaccount-tab-content-inner">
                                                <p>The following addresses will be used on the checkout page by default.</p>
                                                <div class="row">
                                                    <div class="col-md-6 col-12 learts-mb-30">
                                                        <h4>Billing Address <small><a href="#liton_tab_1_6" data-toggle="tab">edit</a></small></h4>
                                                        <address>
                                                            <p><strong>Alex Tuntuni</strong></p>
                                                            <p>1355 Market St, Suite 900 <br>
                                                                San Francisco, CA 94103</p>
                                                            <p>Mobile: (123) 456-7890</p>
                                                        </address>
                                                    </div>
                                                    <div class="col-md-6 col-12 learts-mb-30">
                                                        <h4>Shipping Address <small><a href="#liton_tab_1_6" data-toggle="tab">edit</a></small></h4>
                                                        <address>
                                                            <p><strong>Alex Tuntuni</strong></p>
                                                            <p>1355 Market St, Suite 900 <br>
                                                                San Francisco, CA 94103</p>
                                                            <p>Mobile: (123) 456-7890</p>
                                                        </address>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="tab-pane fade" id="liton_tab_1_6">
                                            <div class="ltn__myaccount-tab-content-inner">
{{--                                                <p>The following addresses will be used on the checkout page by default.</p>--}}
                                                <div class="ltn__form-box">
                                                    <form action="{{route('customer.update-profile')}}">
                                                        <div class="row mb-50">
                                                            <div class="col-md-6">
                                                                <label>First name:</label>
                                                                <input type="text" name="first_name" value="{{Auth::user()->first_name}}">
                                                            </div>
                                                            <div class="col-md-6">
                                                                <label>Last name:</label>
                                                                <input type="text" name="last_name" value="{{Auth::user()->last_name}}">
                                                            </div>
                                                            <div class="col-md-6">
                                                                <label>Display Email:</label>
                                                                <input type="email" name="email" placeholder="example@example.com" value="{{Auth::user()->email}}">
                                                            </div>
                                                        </div>
                                                        <fieldset>
                                                            <legend>Billing Address</legend>
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <label>Address:</label>
                                                                    <input type="text" name="address">
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <label>City:</label>
                                                                    <input type="text" name="city">
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <label>Country:</label>
                                                                    <input type="text" name="country">
                                                                </div>
                                                            </div>
                                                        </fieldset>
                                                        <fieldset>
                                                            <legend>Password change</legend>
                                                            <div class="row">
                                                                <div class="col-md-12">
                                                                    <label>Current password (leave blank to leave unchanged):</label>
                                                                    <div class="password-field-wrap">
                                                                        <input type="password" name="current_password">
                                                                        <button type="button" class="password-toggle" aria-label="Show password">
                                                                            <i class="far fa-eye"></i>
                                                                        </button>
                                                                    </div>
                                                                    <label>New password (leave blank to leave unchanged):</label>
                                                                    <div class="password-field-wrap">
                                                                        <input type="password" name="password">
                                                                        <button type="button" class="password-toggle" aria-label="Show password">
                                                                            <i class="far fa-eye"></i>
                                                                        </button>
                                                                    </div>
                                                                    <label>Confirm new password:</label>
                                                                    <div class="password-field-wrap">
                                                                        <input type="password" name="password_confirmation">
                                                                        <button type="button" class="password-toggle" aria-label="Show password">
                                                                            <i class="far fa-eye"></i>
                                                                        </button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </fieldset>
                                                        <div class="btn-wrapper">
                                                            <button type="submit" class="btn theme-btn-1 btn-effect-1 text-uppercase">Save Changes</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- PRODUCT TAB AREA END -->
                </div>
            </div>
        </div>
    </div>
    <!-- WISHLIST AREA START -->

@endsection