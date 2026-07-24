<!DOCTYPE html>
<html lang="zxx" class="js">

<head>
    <base href="../">
    <meta charset="utf-8">
    <meta name="author" content="Softnio">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="A powerful and conceptual apps base dashboard template that especially build for developers and programmers.">
    <!-- Fav Icon  -->
    <link rel="shortcut icon" href="{{ asset('sk-assets/assets/images/frontend/favicon.png') }}" type="image/png">

    <!-- Page Title  -->
    @isset($data)
        @isset($data['invoice'][0])
    <title>{{$data['invoice'][0]->invoice_no}}</title>
        @endisset
    @endisset
    <!-- StyleSheets  -->
{{--    <link rel="stylesheet" href="{{ asset('sk-assets/css/app.css') }}"/>--}}
    <link rel="stylesheet" href="{{ asset('sk-assets/css/backend/theme.css') }}"/>
    <style>
        body {
            overflow-x: hidden;
        }

        .invoice-print {
            max-width: 940px;
            margin: 1rem auto;
            padding: 0 12px;
            box-sizing: border-box;
            width: 100%;
        }

        .invoice-print .invoice-wrap {
            padding: 1rem 0;
            overflow-x: hidden;
        }

        .invoice-header {
            align-items: center;
        }

        .invoice-brand img {
            display: block;
            width: 223px;
            max-width: 100%;
            height: auto;
        }

        .company-trn {
            color: #8094ae;
            font-size: 12px;
            margin-top: 4px;
        }

        .invoice-print .invoice-desc {
            width: 100%;
            max-width: 100%;
            padding-top: 1rem;
        }

        .invoice-meta-box {
            background-color: #F5F6FA;
            padding: 15px;
            text-align: center;
            border-radius: 4px;
        }

        .invoice-meta-box .title {
            margin-bottom: 4px;
            font-size: 13px;
        }

        .invoice-print .table {
            margin-bottom: 1rem;
        }

        .invoice-print .table th,
        .invoice-print .table td {
            white-space: nowrap;
            vertical-align: middle;
        }

        .invoice-print .table .w-60 {
            white-space: normal;
            min-width: 140px;
        }

        .invoice-print footer {
            padding: 1rem 12px 1.5rem;
            font-size: 12px;
            line-height: 1.6;
            color: #8094ae;
            word-break: break-word;
        }

        .pay-now-btn {
            white-space: nowrap;
        }

        .payment-status-stamp {
            -webkit-transform: rotate(-15deg);
            transform: rotate(-15deg);
            margin: 1rem 0;
        }

        .invoice-print .table td.invoice-note-cell {
            white-space: normal;
            vertical-align: top;
            text-align: left;
            padding-right: 1rem;
        }

        .invoice-note-label {
            font-size: 12px;
            font-weight: 600;
            color: #8094ae;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            margin-bottom: 4px;
        }

        @media (min-width: 768px) {
            .invoice-print {
                margin: 2rem auto;
                padding: 0 1rem;
            }

            .invoice-print .invoice-wrap {
                padding: 1rem;
            }

            .invoice-print .invoice-desc {
                width: 370px;
                max-width: 48%;
                padding-top: 0;
            }
        }

        @media (max-width: 767.98px) {
            .invoice-header > [class*="col-"] {
                margin-bottom: 0.75rem;
            }

            .invoice-brand {
                text-align: center;
            }

            .invoice-brand img {
                width: 160px;
                margin: 0 auto;
            }

            .invoice-company,
            .invoice-title-block,
            .invoice-pay-block {
                text-align: center;
            }

            .invoice-pay-block .btn {
                width: 100%;
                max-width: 220px;
            }

            .invoice-print .invoice-head {
                flex-direction: column;
            }

            .invoice-print .invoice-contact {
                margin-bottom: 1rem;
            }

            .invoice-meta-box {
                padding: 12px 8px;
            }

            .invoice-meta-box .title {
                font-size: 11px;
                letter-spacing: 0.02em;
            }

            .invoice-meta-box span {
                font-size: 13px;
                word-break: break-word;
            }

            .invoice-print .table {
                font-size: 12px;
            }

            .invoice-print .table th,
            .invoice-print .table td {
                padding: 0.5rem 0.4rem;
            }

            .invoice-print footer {
                font-size: 11px;
            }
        }

        @media (max-width: 575.98px) {
            .invoice-print {
                margin: 0.5rem auto;
                padding: 0 8px;
            }

            .invoice-brand img {
                width: 140px;
            }

            .invoice-meta-box .col-4 {
                flex: 0 0 100%;
                max-width: 100%;
                margin-bottom: 10px;
            }

            .invoice-meta-box .col-4:last-child {
                margin-bottom: 0;
            }

            .invoice-print .alert {
                font-size: 13px;
                margin: 0.5rem 0;
            }
        }

        @media print {
            .invoice-print {
                margin: 0;
                max-width: 100%;
                padding: 0;
            }

            .pay-now-btn,
            .alert {
                display: none !important;
            }

            .invoice-print .invoice-desc {
                width: 370px;
                max-width: 48%;
            }

            .invoice-meta-box .col-4 {
                flex: 0 0 33.333333%;
                max-width: 33.333333%;
                margin-bottom: 0;
            }
        }
    </style>

</head>

{{--<body class="bg-white" onload="printPromot()">--}}
<body class="bg-white">

<div class="nk-block">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"
                    aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"
                    aria-label="Close"></button>
        </div>
    @endif
    @if(isset($errors) && $errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ $errors->first() }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"
                    aria-label="Close"></button>
        </div>
    @endif
    @isset($data)
    <div class="invoice invoice-print">
        @isset($data['invoice'][0])
        <div class="invoice-wrap">
            <div class="row invoice-header">
                <div class="col-12 col-md-4">
                    <div class="invoice-brand">
                        <img src="/sk-assets/assets/images/frontend/front-logo.png" alt="Storage Keys">
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-md-4 invoice-company">
                    <h5 class="title">Storage Keys</h5>
                    <div class="company-trn">TRN: 100368001200003</div>
                </div>
                <div class="col-6 col-sm-3 col-md-2 invoice-title-block">
                    <h5 class="title">Tax Invoice</h5>
                    <span>{{$data['invoice'][0]->invoice_no}}</span>
                </div>

                <div class="col-6 col-sm-3 col-md-2 invoice-pay-block">
                    @if($data['invoice'][0]->payment_status !== "Paid")
                    <a href="{{url('pay-now/'.hashid_encode($data['invoice'][0]->id))}}" class="btn btn-primary pay-now-btn">Pay Now</a>
                        @endif
                </div>

            </div>
            <hr>
            <div class="invoice-head">
                <div class="invoice-contact">
                    <span class="overline-title">Bill To</span>
                    <div class="invoice-contact-info">
                        @if($data['invoice'][0]->customer->customer_type == 'company')
                            <h6 class="title">{{$data['invoice'][0]->customer->company_name}}</h6>
                            <h6 class="title">{{$data['invoice'][0]->customer->primaryContact->first_name}} {{$data['invoice'][0]->customer->primaryContact->last_name}}</h6>
                        @else
                            <h6 class="title">{{$data['invoice'][0]->customer->customer_name}}</h6>
                        @endif
                        <ul class="list-plain">
                            <li><em class="icon ni ni-map-pin-fill fs-18px"></em><span>@isset($data['invoice'][0]->customer->address){{$data['invoice'][0]->customer->address}}@endisset<br>{{$data['invoice'][0]->customer->city}}, {{$data['invoice'][0]->customer->country}}</span></li>
                            <li><em class="icon ni ni-call-fill fs-14px"></em><span>{{$data['invoice'][0]->customer->phone}}</span></li>
                        </ul>
                    </div>
                    <span class="overline-title" style="display:block;margin-top:1rem;">Bill From</span>
                    <div class="invoice-contact-info">
                        <h6 class="title">MUFATEEH AL MAKHAZAN</h6>
                    </div>
                </div>
                <div class="invoice-desc">
                    <div class="row invoice-meta-box">
                        <div class="col-4">
                            <h6 class="title">DATE</h6>
                            <span>{{$data['invoice'][0]->invoice_date}}</span>
                        </div>
                        <div class="col-4">
                            <h6 class="title">PLEASE PAY</h6>
                            <span><b>{{$data['invoice'][0]->grand_total}} AED</b></span>
                        </div>
                        <div class="col-4">
                            <h6 class="title">DUE DATE</h6>
                            <span>{{$data['invoice'][0]->due_date}}</span>
                        </div>
                    </div>
                </div>
            </div><!-- .invoice-head -->
            @isset($data['invoice'][0]->invoiceItems)
            <div class="invoice-bills">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <th class="w-150px">DATE</th>
                            <th class="w-60">ACTIVITY</th>
                            <th></th>
                            <th>QTY</th>
                            <th>RATE</th>
                            <th>AMOUNT</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($data['invoice'][0]->invoiceItems as $key => $item)
                            <tr>
                                <td>{{$data['invoice'][0]->invoice_date}}</td>
                                <td>{{$item->item_name}}</td>
                                <td></td>
                                <td>{{$item->quantity}}</td>
                                <td>{{$item->unit_price}}</td>
                                <td>{{$item->total_price}}</td>
                            </tr>
                        @endforeach
                        </tbody>
                        <tfoot>
                        <tr>
                            <td colspan="3" rowspan="4" class="invoice-note-cell">
                                @if($data['invoice'][0]->note)
                                    <div class="invoice-note-label">Invoice Note</div>
                                    <div class="nk-notes ff-italic fs-12px text-soft">{{ $data['invoice'][0]->note }}</div>
                                @endif
                            </td>
                            <td colspan="2">NET SUBTOTAL</td>
                            <td>{{number_format($data['invoice'][0]->taxableSubtotal(), 2)}} AED</td>
                        </tr>

                        <tr>
                            <td colspan="2">VAT TOTAL ({{strtoupper($data['invoice'][0]->vat_type ?? 'exclusive')}})</td>
                            <td>{{number_format($data['invoice'][0]->vatAmount(), 2)}} AED</td>
                        </tr>
                        <tr>
                            <td colspan="2">TOTAL</td>
                            <td>{{$data['invoice'][0]->grand_total}} AED</td>
                        </tr>
                        <tr>
                            <td colspan="2">TOTAL DUE</td>
                            <td><h6>AED {{$data['invoice'][0]->grand_total}}</h6></td>
                        </tr>
                        </tfoot>
                    </table>
                    <h4 class="text-center payment-status-stamp">{{$data['invoice'][0]->payment_status}}</h4>
                    <h5 class="title">VAT SUMMARY</h5>
                    <div class="table-responsive">
                        <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>RATE</th>
                                        <th>NET</th>
                                        <th>VAT</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Federal Tax Authority @ {{$data['invoice'][0]->vat}}%</td>
                                        <td>{{number_format($data['invoice'][0]->taxableSubtotal(), 2)}}</td>
                                        <td>{{number_format($data['invoice'][0]->vatAmount(), 2)}}</td>
                                    </tr>
                                </tbody>
                         </table>

                    </div>
                    <div class="table-responsive">
                    </div>

                </div>
            </div><!-- .invoice-bills -->

            @endisset
        </div><!-- .invoice-wrap -->
        @endisset
    </div><!-- .invoice -->
    @endisset
        <footer class="text-center">Emirates Industrial Area, Sharjah, U.A.E | P.O. Box-71161 | T + 971 65225990 | info@storagekeys.com | www.storagekeys.com</footer>
</div><!-- .nk-block -->
<script>
    function printPromot() {
        window.print();
    }
</script>
</body>

</html>
