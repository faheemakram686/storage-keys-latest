<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    @isset($data['invoice'][0])
        <title>{{ $data['invoice'][0]->invoice_no }}</title>
    @else
        <title>Invoice | Storage Keys</title>
    @endisset
    <style>
        * { box-sizing: border-box; }
        body {
            margin: 0;
            padding: 20px;
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            color: #526484;
            background: #fff;
        }
        .invoice-print {
            max-width: 940px;
            margin: 0 auto;
        }
        .invoice-wrap {
            background: #fff;
        }
        .header-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }
        .header-table td {
            vertical-align: middle;
            padding: 5px;
        }
        .invoice-brand img {
            display: block;
            width: 223px;
            max-height: 60px;
            height: auto;
        }
        .title {
            margin: 0 0 8px;
            color: #364a63;
            font-weight: 600;
        }
        h5.title { font-size: 16px; }
        h6.title { font-size: 13px; }
        hr {
            border: 0;
            border-top: 1px solid #dbdfea;
            margin: 15px 0;
        }
        .invoice-head-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .invoice-head-table td {
            vertical-align: top;
            padding: 0;
        }
        .bill-to-col { width: 55%; }
        .summary-col { width: 45%; }
        .overline-title {
            font-size: 11px;
            letter-spacing: 0.2em;
            color: #8094ae;
            text-transform: uppercase;
            font-weight: 700;
            display: block;
            margin-bottom: 10px;
        }
        .invoice-contact ul {
            list-style: none;
            margin: 0;
            padding: 0;
        }
        .invoice-contact ul li {
            padding: 4px 0;
            line-height: 1.4;
            color: #8094ae;
        }
        .summary-box {
            background-color: #F5F6FA;
            padding: 15px;
            text-align: center;
            width: 100%;
        }
        .summary-box-table {
            width: 100%;
            border-collapse: collapse;
        }
        .summary-box-table td {
            width: 33.33%;
            text-align: center;
            vertical-align: top;
            padding: 5px;
        }
        .summary-box h6.title {
            margin: 0 0 5px;
            color: #364a63;
            text-transform: uppercase;
        }
        .company-trn {
            color: #8094ae;
            font-size: 11px;
            margin-top: 4px;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        .table th {
            color: #854fff;
            font-size: 11px;
            text-transform: uppercase;
            border-bottom: 1px solid #dbdfea;
            padding: 8px 10px;
            text-align: left;
        }
        .table td {
            padding: 8px 10px;
            border-top: 1px solid #dbdfea;
            vertical-align: top;
        }
        .table-striped tbody tr:nth-child(odd) {
            background-color: #f5f6fa;
        }
        .table tfoot td {
            border-top: 1px solid #dbdfea;
            font-weight: 500;
            white-space: nowrap;
        }
        .w-150px { width: 150px; }
        .payment-status {
            text-align: center;
            font-size: 28px;
            font-weight: 700;
            color: #8094ae;
            margin: 20px 0;
            transform: rotate(-15deg);
        }
        .vat-title {
            margin: 15px 0 10px;
            font-size: 14px;
            color: #364a63;
        }
        .invoice-note-cell {
            white-space: normal !important;
            vertical-align: top;
            text-align: left;
            font-weight: 400 !important;
            padding-right: 15px;
        }
        .invoice-note-label {
            font-size: 11px;
            font-weight: 600;
            color: #8094ae;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            margin-bottom: 4px;
        }
        .invoice-note {
            font-style: italic;
            font-size: 11px;
            color: #8094ae;
        }
        footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 15px;
            border-top: 1px solid #dbdfea;
            font-size: 11px;
            color: #8094ae;
        }
    </style>
</head>
<body>
@isset($data)
    @isset($data['invoice'][0])
        <div class="invoice invoice-print">
            <div class="invoice-wrap">
                <table class="header-table">
                    <tr>
                        <td style="width: 40%;">
                            <div class="invoice-brand">
                                @php
                                    $logoPath = public_path('sk-assets/assets/images/frontend/front-logo.png');
                                    $logoData = file_exists($logoPath) ? base64_encode(file_get_contents($logoPath)) : null;
                                @endphp
                                @if($logoData)
                                    <img src="data:image/png;base64,{{ $logoData }}" alt="Storage Keys">
                                @endif
                            </div>
                        </td>
                        <td style="width: 30%;">
                            <h5 class="title">Storage Keys</h5>
                            <div class="company-trn">TRN: 100368001200003</div>
                        </td>
                        <td style="width: 30%;">
                            <h5 class="title">Tax Invoice</h5>
                            <span>{{ $data['invoice'][0]->invoice_no }}</span>
                        </td>
                    </tr>
                </table>

                <hr>

                <table class="invoice-head-table">
                    <tr>
                        <td class="bill-to-col">
                            <div class="invoice-contact">
                                <span class="overline-title">Bill To</span>
                                <div class="invoice-contact-info">
                                    @if($data['invoice'][0]->customer->customer_type == 'company')
                                        <h6 class="title">{{ $data['invoice'][0]->customer->company_name }}</h6>
                                        <h6 class="title">{{ $data['invoice'][0]->customer->primaryContact->first_name }} {{ $data['invoice'][0]->customer->primaryContact->last_name }}</h6>
                                    @else
                                        <h6 class="title">{{ $data['invoice'][0]->customer->customer_name }}</h6>
                                    @endif
                                    <ul class="list-plain">
                                        <li>
                                            @isset($data['invoice'][0]->customer->address)
                                                {{ $data['invoice'][0]->customer->address }}<br>
                                            @endisset
                                            {{ $data['invoice'][0]->customer->city }}, {{ $data['invoice'][0]->customer->country }}
                                        </li>
                                        <li>{{ $data['invoice'][0]->customer->phone }}</li>
                                    </ul>
                                </div>
                                <span class="overline-title" style="display:block;margin-top:12px;">Bill From</span>
                                <div class="invoice-contact-info">
                                    <h6 class="title">MUFATEEH AL MAKHAZAN</h6>
                                </div>
                            </div>
                        </td>
                        <td class="summary-col">
                            <div class="summary-box">
                                <table class="summary-box-table">
                                    <tr>
                                        <td>
                                            <h6 class="title">DATE</h6>
                                            <span>{{ $data['invoice'][0]->invoice_date }}</span>
                                        </td>
                                        <td>
                                            <h6 class="title">PLEASE PAY</h6>
                                            <span><strong>{{ $data['invoice'][0]->grand_total }} AED</strong></span>
                                        </td>
                                        <td>
                                            <h6 class="title">DUE DATE</h6>
                                            <span>{{ $data['invoice'][0]->due_date }}</span>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </td>
                    </tr>
                </table>

                @isset($data['invoice'][0]->invoiceItems)
                    <div class="invoice-bills">
                        <table class="table table-striped">
                            <thead>
                            <tr>
                                <th class="w-150px">DATE</th>
                                <th>ACTIVITY</th>
                                <th></th>
                                <th>QTY</th>
                                <th>RATE</th>
                                <th>AMOUNT</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($data['invoice'][0]->invoiceItems as $item)
                                <tr>
                                    <td>{{ $data['invoice'][0]->invoice_date }}</td>
                                    <td>{{ $item->item_name }}</td>
                                    <td></td>
                                    <td>{{ $item->quantity }}</td>
                                    <td>{{ $item->unit_price }}</td>
                                    <td>{{ $item->total_price }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                            <tfoot>
                            <tr>
                                <td colspan="3" rowspan="4" class="invoice-note-cell">
                                    @if($data['invoice'][0]->note)
                                        <div class="invoice-note-label">Invoice Note</div>
                                        <div class="invoice-note">{{ $data['invoice'][0]->note }}</div>
                                    @endif
                                </td>
                                <td colspan="2">NET SUBTOTAL</td>
                                <td>{{ number_format($data['invoice'][0]->taxableSubtotal(), 2) }} AED</td>
                            </tr>
                            <tr>
                                <td colspan="2">VAT TOTAL ({{ strtoupper($data['invoice'][0]->vat_type ?? 'exclusive') }})</td>
                                <td>{{ number_format($data['invoice'][0]->vatAmount(), 2) }} AED</td>
                            </tr>
                            <tr>
                                <td colspan="2">TOTAL</td>
                                <td>{{ $data['invoice'][0]->grand_total }} AED</td>
                            </tr>
                            <tr>
                                <td colspan="2">TOTAL DUE</td>
                                <td><strong>AED {{ $data['invoice'][0]->grand_total }}</strong></td>
                            </tr>
                            </tfoot>
                        </table>

                        <div class="payment-status">{{ $data['invoice'][0]->payment_status }}</div>

                        <h5 class="vat-title">VAT SUMMARY</h5>
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
                                <td>Federal Tax Authority @ {{ $data['invoice'][0]->vat }}%</td>
                                <td>{{ number_format($data['invoice'][0]->taxableSubtotal(), 2) }}</td>
                                <td>{{ number_format($data['invoice'][0]->vatAmount(), 2) }}</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                @endisset
            </div>
        </div>

        <footer>
            Emirates Industrial Area, Sharjah, U.A.E | P.O. Box-71161 | T + 971 65225990 | info@storagekeys.com | www.storagekeys.com
        </footer>
    @endisset
@endisset
</body>
</html>
