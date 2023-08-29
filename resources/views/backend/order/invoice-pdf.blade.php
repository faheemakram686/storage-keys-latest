<!DOCTYPE html>
<html lang="zxx" class="js">

<head>
    <base href="../">
    <meta charset="utf-8">
    <meta name="author" content="Softnio">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="A powerful and conceptual apps base dashboard template that especially build for developers and programmers.">
    <!-- Fav Icon  -->
    <link rel="shortcut icon" href="./images/favicon.png">
    <!-- Page Title  -->
    <title>Invoice Print | Storage Keys</title>
    <!-- StyleSheets  -->
{{--    <link rel="stylesheet" href="{{ asset('sk-assets/css/app.css') }}"/>--}}
    <link rel="stylesheet" href="{{ asset('sk-assets/css/backend/theme.css') }}"/>
    <style>
        .invoice-print {
            max-width: 940px;
            margin: 2rem auto;
        }
        .invoice {
            position: relative;
        }
        .invoice-print .invoice-wrap {
            padding: 0;
            border: none !important;
        }
        .invoice-wrap {
            /*padding: 1.25rem;*/
            /*border: 1px solid #dbdfea;*/
            border-radius: 4px;
            background: #fff;
        }
        .invoice-brand {
            padding-bottom: 1.5rem;
        }
        .text-center {
            text-align: center !important;
        }

        .invoice-brand img {
            max-height: 60px;
        }

        @media (min-width: 768px)
        {
            .invoice-head {
                flex-direction: row;
            }
        }

        .invoice-head {
            padding-bottom: 1.5rem;
            display: flex;
            justify-content: space-between;
            flex-direction: row;
        }

        @media (min-width: 768px){
            .invoice-desc {
                padding-top: 0;
            }
        }

        .invoice-desc {
            width: 210px;
            padding-top: 1.5rem;
        }

        .overline-title {
            font-size: 11px;
            line-height: 1.2;
            letter-spacing: 0.2em;
            color: #8094ae;
            text-transform: uppercase;
            font-weight: 700;
            font-family: "DM Sans", sans-serif, "Helvetica Neue", Arial, "Noto Sans", sans-serif;
        }

        .invoice-contact .title {
            margin-bottom: 1rem;
        }
        ol, ul {
            list-style: none;
            margin: 0;
            padding: 0;
        }

        .invoice-contact ul li:first-child {
            padding-top: 0;
        }
        .invoice-contact ul li {
            padding: 0.5rem 0;
            line-height: 1.3;
        }

        li {
            list-style: none;
        }
        .invoice-contact ul .icon {
            line-height: 1.3;
            font-size: 1.1em;
            display: inline-block;
            vertical-align: top;
            margin-top: -2px;
            color: #854fff;
            margin-right: 0.5rem;
        }
        .invoice-contact ul .icon + span {
            display: inline-block;
            vertical-align: top;
            color: #8094ae;
        }

        .icon + span, span + .icon {
            margin-left: 0.25rem;
        }
        .invoice-desc .title {
            text-transform: uppercase;
            color: #854fff;
        }
        @media (min-width: 992px)
        {
            h3,.h3 {
                font-size: 2rem;
                letter-spacing: -0.03em;
            }
        }
        .invoice-desc ul li {
            padding: 0.25rem 0;
        }

        .invoice-desc ul span:first-child {
            min-width: 90px;
            display: inline-block;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #8094ae;
        }

        .invoice-desc ul span {
            font-size: 13px;
            font-weight: 500;
            color: #526484;
        }
        .invoice-desc ul span:last-child {
            padding-left: 0.75rem;
        }
        .invoice-desc ul span {
            font-size: 13px;
            font-weight: 500;
            color: #526484;
        }
        @media (min-width: 768px)
        {
            .invoice-bills {
                font-size: 0.875rem;
            }
        }

        .invoice-bills {
            font-size: 12px;
        }
        .table-responsive {
            display: block;
            width: 100%;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }
        .invoice-bills .table {
            min-width: 580px;
        }
        .table {
            width: 100%;
            margin-bottom: 1rem;
            color: #526484;
        }
        table {
            border-collapse: collapse;
        }
        .table thead tr:last-child th {
            border-bottom: 1px solid #dbdfea;
        }
        .invoice-bills .table th {
            color: #854fff;
            font-size: 12px;
            text-transform: uppercase;
            border-top: 0;
        }

        .table td:first-child, .table th:first-child {
            padding-left: 1.25rem;
        }
        .table thead th {
            vertical-align: bottom;
            /*border-bottom: 2px solid #dbdfea;*/
        }
        .table th {
            line-height: 1.1;
        }
        .table th, .table td {
            padding: 0.5rem;
            /*vertical-align: top;*/
            /*border-top: 1px solid #dbdfea;*/
        }
        .table thead tr:last-child th {
            border-bottom: 1px solid #dbdfea;
        }
        .w-150px {
            width: 150px !important;
        }
        th {
            text-align: inherit;
        }
        .table-striped tbody tr:nth-of-type(odd) {
            background-color: #f5f6fa;
        }
        .table td:first-child, .table th:first-child {
            padding-left: 1.25rem;
        }
        .table td:first-child, .table th:first-child {
            padding-left: 1.25rem;
        }
        .table th, .table td {
            padding: 0.5rem;
            vertical-align: top;
            border-top: 1px solid #dbdfea;
        }
        .invoice-bills .table tfoot {
            border-top: 1px solid #dbdfea;
        }
        .invoice-bills .table tfoot td {
            border-top: 0;
            white-space: nowrap;
            padding-top: 0.25rem;
            padding-bottom: 0.25rem;
        }
        .table td:first-child, .table th:first-child {
            padding-left: 1.25rem;
        }
        .table th, .table td {
            padding: 0.5rem;
            vertical-align: top;
            border-top: 1px solid #dbdfea;
        }
        .invoice-bills .table tfoot tr:last-child td:not(:first-child), .invoice-bills .table tfoot tr:first-child td:not(:first-child) {
            font-weight: 500;
            padding-top: 1.25rem;
            padding-bottom: 0.25rem;
        }
        .invoice-bills .table tfoot td {
            border-top: 0;
            white-space: nowrap;
            padding-top: 0.25rem;
            padding-bottom: 0.25rem;
        }
        .table th, .table td {
            padding: 0.5rem;
            vertical-align: top;
            border-top: 1px solid #dbdfea;
        }
        .table td:last-child, .table th:last-child {
            padding-right: 1.25rem;
        }
    </style>
</head>

<body class="bg-white" >
<div class="nk-block">
    @isset($data)
    <div class="invoice invoice-print">
        @isset($data['invoice'][0])
        <div class="invoice-wrap">
            <div class="invoice-brand text-center">
                <img src="{{ asset('sk-assets/assets/images/frontend/front-logo.png') }}" alt="logo">
            </div>
            <div class="invoice-head">
                <div class="invoice-contact">
                    <span class="overline-title">Invoice To</span>
                    <div class="invoice-contact-info">
                        <h4 class="title">{{$data['invoice'][0]->customer->company_name}}</h4>
                        <h4 class="title">{{$data['invoice'][0]->customer->primaryContact->first_name}} {{$data['invoice'][0]->customer->primaryContact->last_name}}</h4>
                        <ul class="list-plain">
                            <li><em class="icon ni ni-map-pin-fill fs-18px"></em><span>@isset($data['invoice'][0]->customer->address){{$data['invoice'][0]->customer->address}}@endisset<br>{{$data['invoice'][0]->customer->city}}, {{$data['invoice'][0]->customer->country}}</span></li>
                            <li><em class="icon ni ni-call-fill fs-14px"></em><span>{{$data['invoice'][0]->customer->phone}}</span></li>
                        </ul>
                    </div>
                </div>
                <div class="invoice-desc">
                    <h3 class="title">Invoice</h3>
                    <ul class="list-plain">
                        <li class="invoice-id"><span>Invoice ID</span>:<span>{{$data['invoice'][0]->invoice_no}}</span></li>
                        <li class="invoice-date"><span>Date</span>:<span>{{$data['invoice'][0]->created_at}}</span></li>
                    </ul>
                </div>
            </div><!-- .invoice-head -->
            @isset($data['invoice'][0]->invoiceItems)
            <div class="invoice-bills">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <th class="w-150px">Item ID</th>
                            <th class="w-60">Description</th>
                            <th>Qty</th>
                            <th>Unit</th>
                            <th>Price</th>
                            <th>Amount</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($data['invoice'][0]->invoiceItems as $key => $item)
                            <tr>
                                <td>{{$key + 1}}</td>
                                <td>{{$item->item_name}}</td>
                                <td>{{$item->quantity}}</td>
                                <td>{{$item->unit}}</td>
                                <td>{{$item->unit_price}}</td>
                                <td>{{$item->total_price}}</td>
                            </tr>
                        @endforeach
                        </tbody>
                        <tfoot>
                        <tr>
                            <td colspan="3"></td>
                            <td colspan="2">Subtotal</td>
                            <td>{{$data['invoice'][0]->sub_total}} AED</td>
                        </tr>

                        <tr>
                            <td colspan="3"></td>
                            <td colspan="2">TAX</td>
                            <td>{{$data['invoice'][0]->vat}} %</td>
                        </tr>
                        <tr>
                            <td colspan="3"></td>
                            <td colspan="2">Grand Total</td>
                            <td>{{$data['invoice'][0]->grand_total}} AED</td>
                        </tr>
                        </tfoot>
                    </table>
                </div>
            </div><!-- .invoice-bills -->
            @endisset
        </div><!-- .invoice-wrap -->
        @endisset
    </div><!-- .invoice -->
    @endisset
</div><!-- .nk-block -->

</body>

</html>