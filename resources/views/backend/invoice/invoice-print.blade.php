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

</head>

<body class="bg-white" onload="printPromot()">
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
<script>
    function printPromot() {
        window.print();
    }
</script>
</body>

</html>