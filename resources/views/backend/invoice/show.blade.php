@extends('backend.layouts.app')
@section('title', '| Invoice')
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
                        <h4 class="title nk-block-title">Invoice Information</h4>
                    </div>
                    <a href="{{url("admin/invoices")}}" class="btn btn-primary btn-sm d-none d-md-inline-flex"><em class="icon ni ni-arrow-left"></em><span>Back</span></a>
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
                                                <h4 class="nk-block-title page-title">Invoice <strong class="text-primary small">#{{$data['invoice'][0]->invoice_no}}</strong>  <span class="badge {{((($data['invoice'][0]->grand_total - $data['payment']) == 0)? 'badge-success':'badge-danger')}} ">{{((($data['invoice'][0]->grand_total - $data['payment']) == 0)? 'Paid':'Up-Paid')}}</span></h4>
                                                @if($data['invoice'][0]->recurring != '0')
                                                <span class="badge badge-outline-primary">Recurring</span>
                                                <span class="badge badge-outline-primary">Cycles Remaining: {{ $data['invoice'][0]->no_cycle}} </span>
                                                    @php
                                                        $currentDate =   \Carbon\Carbon::create($data['invoice'][0]->invoice_date);
                                                         if($data['invoice'][0]->duration_type=="days")
                                                            {
                                                                  $oneMonthLater = $currentDate->addDays($data['invoice'][0]->duration);
                                                            }elseif($data['invoice'][0]->duration_type=="months")
                                                            {
                                                                  $oneMonthLater = $currentDate->addMonths($data['invoice'][0]->duration);
                                                            }elseif($data['invoice'][0]->duration_type=="years")
                                                            {
                                                                  $oneMonthLater = $currentDate->addYears($data['invoice'][0]->duration);
                                                            }elseif($data['invoice'][0]->duration == null && $data['invoice'][0]->duration_type== null)
                                                            {
                                                                  $oneMonthLater = $currentDate->addMonths($data['invoice'][0]->recurring);
                                                            }
                                                        $nextInvoiceDate=$oneMonthLater->format('Y-m-d');
                                                    @endphp
                                                <span class="badge badge-outline-primary"> <em class="icon ni ni-help" data-toggle="tooltip" data-placement="top" title="Invoice will be recreated on specific hour of the day"></em>  Next Invoice Date: {{$nextInvoiceDate}}</span>
                                                @endif

                                                <div class="nk-block-des text-soft">
                                                    <ul class="list-inline">
                                                        <li>Created At: <span class="text-base">{{$data['invoice'][0]->created_at}}</span></li>
                                                    </ul>
                                                </div>
                                            </div>
                                            <div class="d-flex align-center">
                                                <div class="nk-tab-actions me-n1">
                                                    <a class="btn btn-primary {{((($data['invoice'][0]->grand_total - $data['payment']) == 0)? 'disabled':'')}} " title="Payment"  href="{{url('admin/invoice/payment/'.$data['invoice'][0]->id)}}">Payment</a>
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
                                                <a class="btn btn-icon btn-lg btn-white btn-dim btn-outline-primary" href="{{url('admin/print-invoice')}}/{{$data['invoice'][0]->id}}"><em class="icon ni ni-printer-fill"></em></a>
                                            </div><!-- .invoice-actions -->
                                            <div class="invoice-wrap">
{{--                                                <div class="invoice-brand text-center">--}}
{{--                                                    <img src="{{ asset('sk-assets/assets/images/frontend/front-logo.png') }}" srcset="{{ asset('sk-assets/assets/images/frontend/front-logo.png') }}" alt="" >--}}
{{--                                                </div>--}}
                                                <div class="invoice-head">
                                                    <div class="invoice-contact">
                                                        <span class="overline-title">Invoice To</span>
                                                        <div class="invoice-contact-info">
                                                            <h4 class="title">{{$data['invoice'][0]->customer->customer_name}}</h4>
                                                            <h5 class="title">{{$data['invoice'][0]->customer->primaryContact->first_name}} {{$data['invoice'][0]->customer->primaryContact->last_name}}</h5>
                                                            <ul class="list-plain">
                                                                <li><em class="icon ni ni-emails-fill"></em><span>{{$data['invoice'][0]->customer->primaryContact->email}}</span></li>
                                                                <li><em class="icon ni ni-call-fill"></em><span>{{$data['invoice'][0]->customer->primaryContact->phone}}</span></li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                    <div class="invoice-desc mt-5">
                                                        <h4 class="title">Invoice</h4>
                                                        <ul class="list-plain">
                                                            <li class="invoice-id"><span>Invoice No</span>:<span>{{$data['invoice'][0]->invoice_no}}</span></li>
                                                            @if($data['invoice'][0]->contract_id != null)
                                                            <li class="invoice-id"><span>Contract No</span>:<span>Contract# {{$data['invoice'][0]->contract_id}}</span></li>
                                                            @endif
                                                            @if($data['invoice'][0]->order_id != null)
                                                                <li class="invoice-id"><span>Order No</span>:<span>Order# {{$data['invoice'][0]->order_id}}</span></li>
                                                            @endif
                                                            <li class="invoice-date"><span>Date</span>:<span>{{$data['invoice'][0]->created_at}}</span></li>
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
                                                                <th></th>                                                                <th>QTY</th>
                                                                <th>Unit</th>
                                                                <th>Price</th>
                                                                <th>Total</th>
                                                            </tr>
                                                            </thead>
                                                            <tbody id="invoiceItems">
                                                            @foreach($data['invoice'][0]->invoiceItems as $key => $item)
                                                            <tr>
                                                                <td>{{$key + 1}}</td>
                                                                <td></td>
                                                                <td>{{$item->item_name}}</td>
                                                                <td></td>
                                                                <td>{{$item->quantity}}</td>
                                                                <td>{{$item->unit}}</td>
                                                                <td>{{$item->unit_price}}</td>
                                                                <td>{{$item->total_price}}</td>
                                                            </tr>
                                                            @endforeach

                                                            {{--                                                <tr>--}}
                                                            {{--                                                    <td><input type="hidden" name="id[]" placeholder="Enter your Name" class="form-control id_list" /><span>1</span></td>--}}
                                                            {{--                                                    <td><input type="text" name="name[]" placeholder="Enter your Name" class="form-control name_list" /></td>--}}
                                                            {{--                                                    <td><input type="text" name="amount[]" placeholder="Enter your Name" class="form-control amount_list" /></td>--}}
                                                            {{--                                                    <td></td>--}}
                                                            {{--                                                </tr>--}}

                                                            </tbody>
                                                            <tfoot>
                                                            <tr>
                                                                <td colspan="5"></td>
                                                                <td colspan="2">Subtotal</td>
                                                                <td id="subtotal" >{{$data['invoice'][0]->sub_total}} AED</td>
                                                            </tr>
                                                            <tr>
                                                                <td colspan="5"></td>
                                                                <td colspan="2">VAT</td>
                                                                <td id="vat" >{{$data['invoice'][0]->vat}}%</td>
                                                            </tr>
                                                            <tr>
                                                                <td colspan="5"></td>
                                                                <td colspan="2">Grand Total</td>
                                                                <td id="grandtotal" >{{$data['invoice'][0]->grand_total}} AED</td>
                                                            </tr>
                                                            @isset($data['payment'])
                                                            <tr>
                                                                <td colspan="5"></td>
                                                                <td colspan="2">Amount Received</td>
                                                                <td id="amount_received " >{{ $data['payment']}} AED</td>
                                                            </tr>
                                                            <tr>
                                                                <td colspan="5"></td>
                                                                <td colspan="2"  class="text-danger">Amount Due</td>
                                                                <td id="amount_due" class="text-danger" >{{ $data['invoice'][0]->grand_total - $data['payment']}} AED</td>
                                                            </tr>
                                                            @endisset
                                                            </tfoot>
                                                        </table>
                                                        <div class="nk-notes ff-italic fs-12px text-soft"> {{$data['invoice'][0]->note}}</div>
                                                    </div>
                                                </div><!-- .invoice-bills -->
                                            </div><!-- .invoice-wrap -->
                                        </div><!-- .invoice -->
                                    </div><!-- .nk-block -->
                                </div>
                                @include('backend.invoice.aside')
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



