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
{{--    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.3.1/css/bootstrap.css">--}}

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
        .h3{
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
        <div class="nk-block nk-block-lg">
        <div class="nk-block">
            <div class="invoice">
                <div class="invoice-wrap">
                    <div class="invoice-bills">
                        <div class="table-responsive">
                            @isset($data)
                                {{--    {{dd($data)}}--}}
                                <div class="justify-content-center checkout-page">
                                    @foreach ($data['contract'] as $contract)
                                        <div class="container">

                                            <div class="row">
                                                <div class="col-12 col-sm-12 col-md-12 col-lg-12 details-section">
                                                    <form method="post" action="#" id="EstimateForm111">
                                                        @csrf
                                                        <input type="hidden" name="contract_id" id="contract_id" value="{{$contract->id}}">
                                                        <div class="row reservations-sections">

                                                            <div class="offset-md-1 offset-lg-1 col-12 col-sm-12 col-md-9 col-lg-7 term-section-body">
                                                                <div class="row">
                                                                    <div class=" col-12 col-sm-12 col-md-10 col-lg-12">
                                                                        @isset($contract->contractTemplate)
                                                                            {!! $contract->contractTemplate->temp_body !!}
                                                                        @endisset
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-12 col-sm-12 col-md-3 col-lg-3  order-summary" >
                                                                <div class="row locations-section" >
                                                                    <div class="col-12 order-section-body">

                                                                        @if($contract->is_signed == 'Signed')
                                                                            <div class="separator-item"></div>
                                                                            <div class="row">
                                                                                @if($contract->sign_image != null)
                                                                                    <div class="col-lg-12 col-md-12">
                                                                                        <div class="text-center">
{{--                                                                                             {{public_path()}}--}}
                                                                                            <img src="{{ storage_path('app/public/uploads/648c2b05aeb97.png') }}" alt="image not found" class="img-thumbnail">
{{--                                                                                            <img src="{{ url("storage/uploads/contract_sign_images/".$contract->sign_image) }}" alt="image not found" class="img-thumbnail">--}}
{{--                                                                                            <img src="{{  public_path('storage/uploads/contract_sign_images').'/'.$contract->sign_image }}" style="width: 100px; height: 100px">--}}
{{--                                                                                            <img src="{{ 'http://localhost/public/storage/uploads/contract_sign_images/'.$contract->sign_image}}" alt="image not found" class="img-thumbnail" >--}}
{{--                                                                                            <img src="{{ asset('storage/uploads/contract_sign_images').'/'.$contract->sign_image}}" alt="image not found" class="img-thumbnail" >--}}
                                                                                            <h4 style="color:#FF8820;">Signature____________________</h4>
                                                                                        </div>

                                                                                    </div>
                                                                                @endif
                                                                            </div>
                                                                        @endif
                                                                    </div>

                                                                </div>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                            @endforeach
                                        </div>
                                </div>

                            @endisset
                            <div class="nk-notes ff-italic fs-12px text-soft"> Contract was created on a computer and is valid without the signature and seal. </div>
                        </div>
                    </div><!-- .invoice-bills -->
                </div><!-- .invoice-wrap -->
            </div><!-- .invoice -->
        </div><!-- .nk-block -->
        </div>
        <!-- nk-block -->
    </div>
</body>
</html>
{{--@endsection--}}



