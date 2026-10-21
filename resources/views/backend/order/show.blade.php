@extends('backend.layouts.app')
@section('title', '| Order')
@section('content')
    <style>
        a.disabled {
            pointer-events: none;
            cursor: default;
        }
    </style>


    <div class="container-fluid">
        <div class="nk-content-inner">
            <div class="nk-block-head">
                <div class="nk-block-between">
                    <div class="nk-block-head-content">
                        <h4 class="title nk-block-title">Order Information</h4>
                    </div>
                    <a href="{{url("admin/order")}}" class="btn btn-primary btn-sm d-none d-md-inline-flex"><em class="icon ni ni-arrow-left"></em><span>Back</span></a>
                </div>
            </div>
            <div class="nk-content-body">
                <div class="nk-block">
                    @isset($data)
                        <div class="card">
                            <div class="card-aside-wrap">
                                <div class="card-inner card-inner-lg">
                                    <div class="nk-block-head">
                                        <div class="nk-block-between g-3">
                                            <div class="nk-block-head-content">
                                                <h4 class="nk-block-title page-title">Order <strong class="text-primary small">#{{$data['invoice'][0]->id}}</strong>  </h4>
                                                <div class="nk-block-des text-soft">
                                                    <ul class="list-inline">
                                                        <li>Created At: <span class="text-base">{{$data['invoice'][0]->created_at}}</span></li>
                                                    </ul>
                                                </div>
                                            </div>
                                            <div class="d-flex align-center">
                                                <div class="nk-tab-actions me-n1">
                                                    @php
                                                        $linkedInvoice = $data['invoice'][0]->invoice ?? null;
                                                    @endphp
                                                    @if($linkedInvoice)
                                                        <a class="btn btn-primary" title="View Invoice" href="{{url('admin/invoice/detail/'.$linkedInvoice->id)}}">View Invoice</a>
                                                    @else
                                                        <a class="btn btn-primary" title="Generate Invoice" href="{{url('admin/generate/invoice/'.$data['invoice'][0]->id)}}">Generate Invoice</a>
                                                    @endif
                                                </div>
                                                <div class="nk-block-head-content align-self-start d-lg-none">
                                                    <a href="#" class="toggle btn btn-icon btn-trigger" data-target="userAside"><em class="icon ni ni-menu-alt-r"></em></a>
                                                </div>
                                            </div>
                                        </div>

                                    </div><!-- .nk-block-head -->
                                    <div class="nk-block">
                                        <div class="invoice">
                                            <div class="invoice-action">
                                                <a class="btn btn-icon btn-lg btn-white btn-dim btn-outline-primary" href="{{url('admin/print-order')}}/{{$data['invoice'][0]->id}}"><em class="icon ni ni-printer-fill"></em></a>
                                            </div><!-- .invoice-actions -->
                                            <div class="invoice-wrap">
{{--                                                <div class="invoice-brand text-center">--}}
{{--                                                    <img src="{{ asset('sk-assets/assets/images/frontend/front-logo.png') }}" srcset="{{ asset('sk-assets/assets/images/frontend/front-logo.png') }}" alt="" >--}}
{{--                                                </div>--}}
                                                <div class="invoice-head">
                                                    <div class="invoice-contact">
                                                        <span class="overline-title">Invoice To</span>
                                                        <div class="invoice-contact-info">
                                                            @php
                                                                $orderCustomer = $data['invoice'][0]->customer;
                                                                $orderContact = $orderCustomer->primaryContact ?? optional($orderCustomer->contact)->first();
                                                            @endphp
                                                            <h4 class="title">{{ $orderCustomer->company_name ?: $orderCustomer->customer_name }}</h4>
                                                            <h5 class="title">{{ trim(($orderContact->first_name ?? '') . ' ' . ($orderContact->last_name ?? '')) }}</h5>
                                                            <ul class="list-plain">
                                                                <li><em class="icon ni ni-emails-fill"></em><span>{{ $orderContact->email ?? $orderCustomer->email }}</span></li>
                                                                <li><em class="icon ni ni-call-fill"></em><span>{{ $orderContact->phone ?? $orderCustomer->phone }}</span></li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                    <div class="invoice-desc mt-5">
                                                        <h4 class="title">Order</h4>
                                                        <ul class="list-plain">
                                                            <li class="invoice-id"><span>Order No</span>-<span>{{$data['invoice'][0]->id}}</span></li>
                                                            <li class="invoice-date"><span>Date</span>:<span>{{$data['invoice'][0]->created_at}}</span></li>
                                                            @php
                                                                $orderInvoice = $data['invoice'][0]->invoice ?? null;
                                                                $orderPayment = $orderInvoice
                                                                    ? optional($orderInvoice->payments)->sortByDesc('id')->first()
                                                                    : null;
                                                            @endphp
                                                            @if($orderInvoice)
                                                                <li class="invoice-id">
                                                                    <span>Invoice</span>:
                                                                    <span>
                                                                        <a href="{{url('admin/invoice/detail/'.$orderInvoice->id)}}">{{ $orderInvoice->invoice_no }}</a>
                                                                    </span>
                                                                </li>
                                                            @endif
                                                            @if(!empty($data['invoice'][0]->gateway_reference))
                                                                <li class="invoice-id"><span>Payment Ref</span>:<span>{{ $data['invoice'][0]->gateway_reference }}</span></li>
                                                            @endif
                                                            @if($orderPayment)
                                                                <li class="invoice-id"><span>Paid</span>:<span>{{ number_format((float) $orderPayment->amount_received, 2) }} AED</span></li>
                                                                <li class="invoice-id"><span>Status</span>:<span class="text-success">Paid</span></li>
                                                            @endif
                                                        </ul>
                                                    </div>
                                                </div><!-- .invoice-head -->
                                                <div class="invoice-bills">
                                                    <div class="table-responsive">
                                                        <table class="table table-striped" id="dynamic_field">
                                                            <thead>
                                                            <tr>
                                                                <th class="w-70px">Sr No#</th>
                                                                <th></th>
                                                                <th class="w-60">Description</th>
                                                                <th></th>
                                                                <th>QTY</th>
                                                                <th>Price</th>
                                                                <th>Total</th>
                                                            </tr>
                                                            </thead>
                                                            <tbody id="invoiceItems">
                                                            @foreach($data['invoice'][0]->orderItems as $key => $item)
                                                            <tr>
                                                                <td>{{$key + 1}}</td>
                                                                <td></td>
                                                                <td>{{ $item->item_name ?: optional($item->productdetail)->p_name }}</td>
                                                                <td></td>
                                                                <td>{{$item->qty}}</td>
                                                                <td>{{$item->price}}</td>
                                                                <td>{{$item->total}}</td>
                                                            </tr>
                                                            @endforeach


                                                            </tbody>
                                                            <tfoot>
                                                            <tr>
                                                                <td colspan="4"></td>
                                                                <td colspan="2">Total</td>
                                                                <td id="subtotal" >{{$data['invoice'][0]->sub_amount}} AED</td>
                                                            </tr>



                                                            </tfoot>
                                                        </table>
                                                        <div class="nk-notes ff-italic fs-12px text-soft"> {{$data['invoice'][0]->notes}}</div>
                                                    </div>
                                                </div><!-- .invoice-bills -->
                                            </div><!-- .invoice-wrap -->
                                        </div><!-- .invoice -->
                                    </div><!-- .nk-block -->
                                </div>
                                @include('backend.order.aside')
                                <!-- card-aside -->
                            </div>
                            <!-- .card-aside-wrap -->
                        </div>
                    @endisset
                    <!-- .card -->
                </div>
                <!-- .nk-block -->
            </div>

        </div>
    </div>

    <script>




    </script>
@endsection



