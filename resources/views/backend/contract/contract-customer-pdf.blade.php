<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Contract | Storage Keys</title>
    <style>
        * { box-sizing: border-box; }
        body {
            margin: 0;
            padding: 18px 20px;
            font-family: DejaVu Sans, sans-serif;
            font-size: 11px;
            line-height: 1.45;
            color: #4A392D;
            background: #fff;
        }
        .contract-print { width: 100%; }
        .header-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 8px;
        }
        .header-table td {
            vertical-align: middle;
            padding: 0;
        }
        .brand-logo {
            display: block;
            width: 170px;
            max-height: 52px;
            height: auto;
        }
        .header-meta {
            text-align: right;
            color: #526484;
        }
        .header-meta .company {
            margin: 0 0 2px;
            font-size: 14px;
            font-weight: 700;
            color: #364a63;
        }
        .header-meta .doc-title {
            margin: 0;
            font-size: 11px;
            color: #8094ae;
            text-transform: uppercase;
            letter-spacing: 0.08em;
        }
        .header-rule {
            border: 0;
            border-top: 2px solid #FF8820;
            margin: 8px 0 14px;
        }
        .contract-body {
            width: 100%;
        }
        .contract-body p {
            margin: 0 0 7px;
            line-height: 1.45;
        }
        .contract-body strong { font-weight: 700; }
        .contract-body figure.table,
        .contract-body figure {
            display: block;
            margin: 6px 0 10px;
            padding: 0;
            width: 100%;
        }
        .contract-body table {
            width: 100% !important;
            border-collapse: collapse;
            table-layout: fixed;
            margin: 0 0 8px;
        }
        .contract-body table td,
        .contract-body table th {
            border: 1px solid #cfd6e4;
            padding: 6px 7px;
            vertical-align: top;
            word-wrap: break-word;
            overflow-wrap: break-word;
            font-size: 10.5px;
            line-height: 1.4;
        }
        /* Terms & Conditions bilingual table */
        .contract-body table.tc-table td {
            width: 50%;
            padding: 7px 8px;
            vertical-align: top;
            font-size: 9.5px;
            line-height: 1.4;
        }
        .contract-body table colgroup col {
            width: 50%;
        }
        .contract-body table td[dir="rtl"],
        .contract-body [dir="rtl"] {
            direction: rtl;
            text-align: right;
            unicode-bidi: embed;
        }
        .contract-body table td p {
            margin: 0 0 5px;
            font-size: 9.5px;
            line-height: 1.4;
        }
        .contract-body table td[style*="background:#f3f5f9"],
        .contract-body table td[style*="background:#f7f8fa"] {
            background: #f3f5f9 !important;
            font-size: 11px;
            vertical-align: middle !important;
            text-align: center;
        }
        .section-title {
            color: #FF8820;
            font-size: 12px;
            font-weight: 700;
            margin: 10px 0 6px;
            text-transform: uppercase;
            letter-spacing: 0.04em;
        }
        .sign-box {
            margin-top: 14px;
            text-align: center;
        }
        .sign-box img {
            height: 90px;
            width: auto;
            max-width: 180px;
        }
        .sign-label {
            margin: 4px 0 0;
            color: #FF8820;
            font-size: 12px;
            font-weight: 700;
        }
        .contract-note {
            margin-top: 16px;
            padding-top: 8px;
            border-top: 1px solid #dbdfea;
            font-style: italic;
            font-size: 10px;
            color: #8094ae;
        }
        .page-break,
        [style*="page-break-before"] {
            page-break-before: always;
            display: block;
            height: 0;
            margin: 0;
            padding: 0;
        }
    </style>
</head>
<body>
@isset($data)
    @foreach ($data['contract'] as $contract)
        <div class="contract-print">
            <table class="header-table">
                <tr>
                    <td style="width: 42%;">
                        @php
                            $logoPath = public_path('sk-assets/assets/images/frontend/front-logo.png');
                            $logoData = is_file($logoPath) ? base64_encode(file_get_contents($logoPath)) : null;
                        @endphp
                        @if($logoData)
                            <img class="brand-logo" src="data:image/png;base64,{{ $logoData }}" alt="Storage Keys">
                        @endif
                    </td>
                    <td class="header-meta" style="width: 58%;">
                        <p class="company">Storage Keys</p>
                        <p class="doc-title">Self Storage License Agreement</p>
                        <p style="margin:4px 0 0;font-size:11px;">
                            Agreement No: <strong>{{ $contract->id }}</strong>
                            @if(!empty($contract->start_date))
                                &nbsp;|&nbsp; Start: <strong>{{ \Carbon\Carbon::parse($contract->start_date)->format('d M Y') }}</strong>
                            @endif
                        </p>
                    </td>
                </tr>
            </table>
            <hr class="header-rule">

            <div class="contract-body">
                @isset($contract->contractTemplate)
                    {!! $contract->contractTemplate->temp_body !!}
                @endisset
            </div>

            @if($contract->is_signed == 'Signed' && !empty($contract->sign_image))
                @php
                    $signPath = public_path('storage/uploads/contract_sign_images/'.$contract->sign_image);
                    $signData = is_file($signPath) ? base64_encode(file_get_contents($signPath)) : null;
                @endphp
                @if($signData)
                    <div class="sign-box">
                        <img src="data:image/png;base64,{{ $signData }}" alt="Signature">
                        <p class="sign-label">Signature</p>
                    </div>
                @endif
            @endif

            <div class="contract-note">
                Contract was created on a computer and is valid without the signature and seal.
            </div>
        </div>
    @endforeach
@endisset
</body>
</html>
