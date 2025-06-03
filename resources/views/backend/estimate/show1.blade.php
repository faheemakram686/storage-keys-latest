{{--@extends('backend.layouts.app')--}}
{{--@section('title', '| Estimate')--}}
{{--@section('content')--}}
        <!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
{{--    <link rel="stylesheet" href="{{ asset('sk-assets/css/app.css') }}"/>--}}
{{--    <link rel="stylesheet" href="{{ asset('sk-assets/css/backend/theme.css') }}"/>--}}
{{--    <link rel="stylesheet" href="{{ asset('sk-assets/css/backend/dataTables.css') }}"/>--}}
{{--    <link rel="stylesheet" href="{{ asset('sk-assets/css/backend/dataTableResponsive.css') }}"/>--}}
{{--    <link rel="stylesheet" href="{{ asset('sk-assets/css/backend/dataTableRowGroup.css') }}"/>--}}
{{--    <link rel="stylesheet" href="{{ asset('sk-assets/css/custom.css') }}"/>--}}
{{--    <link rel="stylesheet" href="{{ asset('sk-assets/css/toastr.css') }}"/>--}}
{{--    <script src="https://cdn.ckeditor.com/ckeditor5/36.0.1/classic/ckeditor.js"></script>--}}
{{--    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>--}}
{{--    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>--}}
    <style>
        .ck-editor__editable {
            min-height: 300px !important;
        }
        .nk-block-head {
            position: relative;
            padding-bottom: 1.25rem;
        }
        .card {
            box-shadow: 0px 1px 3px 0px rgba(54, 74, 99, 0.05);
        }
        .components-preview .card-preview > .card-inner {
            padding: 1.25rem;
        }
        .invoice {
            position: relative;
        }
        .invoice-action {
            position: absolute;
            right: 1.25rem;
            top: 1.25rem;
        }
        .invoice-wrap {
            padding: 1.25rem;
            border: 1px solid #dbdfea;
            border-radius: 4px;
            background: #fff;
        }
        .invoice-brand {
            padding-bottom: 1.5rem;
        }
        .text-center {
            text-align: center !important;
        }
        .invoice-head {
            padding-bottom: 1.5rem;
            display: flex;
            justify-content: space-between;
            flex-direction: row;
        }
        @media (min-width: 300px)
        {
            .invoice-head {
                flex-direction: row;
            }
            .invoice-desc {
                padding-top: 0;
            }
            .invoice-bills {
                font-size: 0.875rem;
            }
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
        .components-preview h4.title, .components-preview h5.title {
            font-size: 1.25rem;
            letter-spacing: -0.01rem;
            font-family: "DM Sans", sans-serif;
            font-weight: 500;
        }
        ol, ul {
            list-style: none;
            margin: 0;
            padding: 0;
        }
        .invoice-desc {
            width: 210px;
            padding-top: 1.5rem;
        }
        .invoice-bills {
            font-size: 12px;
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
        .ni {
            font-family: "Nioicon" !important;
            speak: never;
            font-style: normal;
            font-weight: normal;
            font-variant: normal;
            text-transform: none;
            line-height: 1;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }
        .invoice-contact ul .icon + span {
            display: inline-block;
            vertical-align: top;
            color: #8094ae;
        }
        .icon + span, span + .icon {
            margin-left: 0.25rem;
        }
        .invoice-contact ul li:last-child {
            padding-bottom: 0;
        }

        .invoice-desc .title {
            text-transform: uppercase;
            color: #854fff;
        }
        @media (min-width: 992px)
        .h3 {
                font-size: 2rem;
                letter-spacing: -0.03em;
            }
        invoice-desc ul li {
            padding: 0.25rem 0;
        }
        .invoice-desc ul span:first-child {
            min-width: 40px;
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
        .card .table {
            margin-bottom: 0;
        }
        .table {
            width: 100%;
            margin-bottom: 1rem;
            color: #526484;
        }
        .table {
            display: table;
        }
        table {
            border-collapse: collapse;
        }
        table {
            text-indent: 0;
            border-color: inherit;
            border-collapse: collapse;
        }
        .card .table tr:first-child th:first-child {
            border-top-left-radius: 4px;
        }
        .card .table tr:first-child th, .card .table tr:first-child td {
            border-top: none;
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
            border-bottom: 2px solid #dbdfea;
        }
        .table th {
            line-height: 1.1;
        }
        .table th, .table td {
            padding: 0.5rem;
            vertical-align: top;
            border-top: 1px solid #dbdfea;
        }
        .w-150px {
            width: 150px !important;
        }
        th {
            text-align: inherit;
        }
        .w-60 {
            width: 60% !important;
        }
        .card .table tr:first-child th, .card .table tr:first-child td {
            border-top: none;
        }
        .table td:first-child, .table th:first-child {
            padding-left: 1.25rem;
        }
        .table th, .table td {
            padding: 0.5rem;
            vertical-align: top;
            border-top: 1px solid #dbdfea;
        }
        .fs-12px {
            font-size: 12px;
        }
        .text-soft {
            color: #8094ae !important;
        }
        .font-italic, .ff-italic {
            font-style: italic !important;
        }
        .invoice-bills .table tfoot {
            border-top: 1px solid #dbdfea;
        }
        .card .table tr:first-child th, .card .table tr:first-child td {
            border-top: none;
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
        @media (min-width: 992px)
        {
            .page-title {
                font-size: 1.75rem;
            }
        }

        .page-title {
            font-family: "DM Sans", sans-serif;
            font-size: 1.5rem;
            font-weight: 700;
        }

    </style>
</head>
<body>
    <div class="components-preview wide-md mx-auto">

        <div class="nk-block">
            <div class="invoice">
                <div class="invoice-wrap">

                    <div class="invoice-head">
                        <div class="invoice-contact">
                            <span class="overline-title">Estimate To</span>
                            <div class="invoice-contact-info">
                                <h4 class="title">{{$data['estimate'][0]->f_name}} {{$data['estimate'][0]->l_name}}</h4>
                                <ul class="list-plain">
                                    <li><em class="icon ni ni-emails-fill"></em><span>{{$data['estimate'][0]->email}}</span></li>
                                    <li><em class="icon ni ni-call-fill"></em><span>{{$data['estimate'][0]->phone}}</span></li>
                                </ul>
                            </div>
                        </div>
                        <div class="invoice-desc">
                            <h4 class="title">Estimate</h4>
                            <ul class="list-plain">
                                <li class="invoice-id"><span>Estiamte ID</span>:<span>{{$data['estimate'][0]->id}}</span></li>
                                <li class="invoice-date"><span>Date</span>:<span>{{$data['estimate'][0]->created_at}}</span></li>
                            </ul>
                        </div>
                    </div><!-- .invoice-head -->
                    <div class="invoice-bills">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                <tr>
                                    <th class="w-150px">Item ID</th>
                                    <th class="w-60">Description</th>
                                    <th></th>
                                    <th></th>
                                    <th>Amount</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td>1</td>
                                    <td>{{$data['estimate'][0]->storageunit->storage_unit_name}}</td>
                                    <td></td>
                                    <td></td>
                                    <td>{{$data['estimate'][0]->unit_price}}</td>
                                </tr>
                                @if($data['estimate'][0]->estimateAddon)
                                        @php $addonAmount = 0; @endphp
                                    @foreach($data['estimate'][0]->estimateAddon as $addon)
                                        @php
                                            $addonAmount += $addon->price;
                                         @endphp
                                        <tr>
                                            <td>{{$addon->id}}</td>
                                            <td>{{$addon->addon->name}}</td>
                                            <td></td>
                                            <td></td>
                                            <td>{{$addon->price}}</td>
                                        </tr>
                                    @endforeach
                                @endif
                                @if($data['estimate'][0]->insurace !='nothanks')
                                        <tr>
                                            <td>{{$data['estimate'][0]->id}}</td>
                                            <td>Insurance</td>
                                            <td></td>
                                            <td></td>
                                            <td>25</td>
                                        </tr>
                                @endif
                                </tbody>
                                <tfoot>
                                <tr>
                                    <td colspan="2"></td>
                                    <td colspan="2">Subtotal</td>
                                    <td>{{$data['estimate'][0]->unit_price + $addonAmount + (($data['estimate'][0]->insurance !='nothanks')? 25:0) }} AED</td>
                                </tr>
                                <tr>
                                    <td colspan="2"></td>
                                    <td colspan="2">TAX</td>
                                    <td>0.00 AED</td>
                                </tr>
                                <tr>
                                    <td colspan="2"></td>
                                    <td colspan="2">Grand Total</td>
                                    <td>{{$data['estimate'][0]->unit_price + $addonAmount + (($data['estimate'][0]->insurance !='nothanks')? 25:0)}} AED</td>
                                </tr>
                                </tfoot>
                            </table>
                            <div class="nk-notes ff-italic fs-12px text-soft"> Estimate was created on a computer and is valid without the signature and seal. </div>
                        </div>
                    </div><!-- .invoice-bills -->
                </div><!-- .invoice-wrap -->
            </div><!-- .invoice -->
        </div><!-- .nk-block -->
        <!-- nk-block -->
    </div>
</body>
</html>
{{--@endsection--}}



