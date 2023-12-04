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

</head>

<body class="bg-white" onload="printPromot()">
<div class="nk-block">
    @isset($data)
    <div class="invoice invoice-print">
        @isset($data['invoice'][0])
        <div class="invoice-wrap">
            <div class="row">
                <div class="col-4">
                    <div class="invoice-brand ">
                        <img src="{{ asset('sk-assets/assets/images/frontend/front-logo.png') }}" alt="logo">
                    </div>
                </div>
                <div class="col-4">
                    <h5 class="title">Storage Keys</h5>
                </div>
                <div class="col-4">
                    <h5 class="title">Tax Invoice</h5>
                    <span >{{$data['invoice'][0]->invoice_no}}</span>
                </div>
            </div>
            <hr>
            <div class="invoice-head">
                <div class="invoice-contact">
                    <span class="overline-title">Bill To</span>
                    <div class="invoice-contact-info">
                        <h6 class="title">{{$data['invoice'][0]->customer->customer_name}}</h6>
                        <h6 class="title">{{$data['invoice'][0]->customer->primaryContact->first_name}} {{$data['invoice'][0]->customer->primaryContact->last_name}}</h6>
                        <ul class="list-plain">
                            <li><em class="icon ni ni-map-pin-fill fs-18px"></em><span>@isset($data['invoice'][0]->customer->address){{$data['invoice'][0]->customer->address}}@endisset<br>{{$data['invoice'][0]->customer->city}}, {{$data['invoice'][0]->customer->country}}</span></li>
                            <li><em class="icon ni ni-call-fill fs-14px"></em><span>{{$data['invoice'][0]->customer->phone}}</span></li>
                        </ul>
                    </div>
                </div>
                <div class="invoice-desc">
                    <div class="row" style="background-color: #F5F6FA;padding: 15px;text-align: center;">
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
                            <td colspan="3"></td>
                            <td colspan="2">SUBTOTAL</td>
                            <td>{{$data['invoice'][0]->sub_total}} AED</td>
                        </tr>

                        <tr>
                            <td colspan="3"></td>
                            <td colspan="2">VAT TOTAL</td>
                            <td>{{($data['invoice'][0]->sub_total * $data['invoice'][0]->vat / 100)}} AED</td>
                        </tr>
                        <tr>
                            <td colspan="3"></td>
                            <td colspan="2">TOTAL</td>
                            <td>{{$data['invoice'][0]->grand_total}} AED</td>
                        </tr>
                        <tr>
                            <td colspan="3"></td>
                            <td colspan="2">TOTAL DUE</td>
                            <td><h6>AED {{$data['invoice'][0]->grand_total}}</h6></td>
                        </tr>
                        </tfoot>
                    </table>
                    <h4 class="text-center"
                        style=" -webkit-transform: rotate(-15deg);">{{$data['invoice'][0]->payment_status}}</h4>
                    <h5 class="title">VAT SUMMARY</h5>
                    <div class="table-responsive">
                        <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>RATE</th>
                                        <th>VAT</th>
                                        <th>NET</th>
                                    </tr>
                                </thead>
                                <tobody>
                                    <tr>
                                        <td>Federal Tax Authority @ {{$data['invoice'][0]->vat}}%</td>
                                        <td>{{$data['invoice'][0]->vat}}</td>
                                        <td>{{($data['invoice'][0]->sub_total * $data['invoice'][0]->vat / 100)}}</td>
                                    </tr>
                                </tobody>
                         </table>
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