@php
    $companyTitle = property_exists($payslip_settings, 'title') && $payslip_settings->title
        ? $payslip_settings->title
        : ($settings->tenant_name ?? '');

    if (property_exists($payslip_settings, 'address') && $payslip_settings->address) {
        $companyAddress = $payslip_settings->address;
    } else {
        $companyAddress = trim(implode(', ', array_filter([
            $settings->address ?? null,
            $settings->area ?? null,
            $settings->city ?? null,
            $settings->zip_code ?? null,
            $settings->country ?? null,
        ])));
    }

    $logoSrc = property_exists($payslip_settings, 'logo') && $payslip_settings->logo
        ? asset($payslip_settings->logo)
        : (property_exists($settings, 'tenant_logo') && $settings->tenant_logo
            ? asset($settings->tenant_logo)
            : asset('sk-assets/assets/images/frontend/front-logo.png'));

    $user = $payslip->user;
    $profile = $user->profile;
    $employeeNo = optional($profile)->employee_id;
    $joiningDate = optional($profile)->joining_date
        ? \Carbon\Carbon::parse($profile->joining_date)->format('d M Y')
        : '—';
    $designation = optional($user->designation)->name ?: '—';
    $location = optional($profile)->res_visa_loc
        ?: (optional($user->department)->name ?: (optional($profile)->address ?: '—'));

    $start = \Carbon\Carbon::parse($payslip->start_date);
    $end = \Carbon\Carbon::parse($payslip->end_date);
    $workingDays = $start->diffInDays($end) + 1;
    $monthLabel = $start->format('M Y');

    $baseEarned = round($payslip->net_salary - ($totalAllowance - $totalDeduction), 2);
    $unpaidLeaveDays = 0;
    if ($salaryAmount > 0 && $baseEarned < $salaryAmount && $workingDays > 0) {
        $daily = $salaryAmount / $workingDays;
        if ($daily > 0) {
            $unpaidLeaveDays = (int) round(($salaryAmount - $baseEarned) / $daily);
        }
    }

    $basicMaster = round((float) $salaryAmount, 2);
    $earnings = [];

    if ($payslip->consider_type != 'none' && $baseEarned > $salaryAmount) {
        $earnings[] = ['name' => 'BASIC', 'master' => $basicMaster, 'amount' => $basicMaster];
        $ot = round($baseEarned - $salaryAmount, 2);
        $earnings[] = [
            'name' => strtoupper(__t('overtime_earning')),
            'master' => $ot,
            'amount' => $ot,
        ];
    } elseif ($payslip->consider_type != 'none' && $baseEarned < $salaryAmount) {
        $earnings[] = ['name' => 'BASIC', 'master' => $basicMaster, 'amount' => $baseEarned];
    } else {
        $earnings[] = ['name' => 'BASIC', 'master' => $basicMaster, 'amount' => $basicMaster];
    }

    foreach ($beneficiaries as $beneficiary) {
        if (($beneficiary->beneficiary->type ?? null) !== 'allowance') {
            continue;
        }
        $amt = $beneficiary->is_percentage == 1
            ? round(($salaryAmount / 100) * $beneficiary->amount, 2)
            : round((float) $beneficiary->amount, 2);
        $label = strtoupper($beneficiary->beneficiary->name);
        if ($beneficiary->is_percentage == 1) {
            $label .= ' (' . $beneficiary->amount . '%)';
        }
        $earnings[] = ['name' => $label, 'master' => $amt, 'amount' => $amt];
    }

    $deductions = [];
    foreach ($beneficiaries as $beneficiary) {
        if (($beneficiary->beneficiary->type ?? null) !== 'deduction') {
            continue;
        }
        $amt = $beneficiary->is_percentage == 1
            ? round(($salaryAmount / 100) * $beneficiary->amount, 2)
            : round((float) $beneficiary->amount, 2);
        $label = strtoupper($beneficiary->beneficiary->name);
        if ($beneficiary->is_percentage == 1) {
            $label .= ' (' . $beneficiary->amount . '%)';
        }
        $deductions[] = ['name' => $label, 'amount' => $amt];
    }

    $totalEarningsMaster = array_sum(array_column($earnings, 'master'));
    $totalEarningsAmount = array_sum(array_column($earnings, 'amount'));
    $totalDeductionsAmount = array_sum(array_column($deductions, 'amount'));
    $netPay = round((float) $payslip->net_salary, 2);

    $currencyCode = strtoupper($settings->currency_code ?? 'AED');
    $whole = (int) floor($netPay);
    $fraction = (int) round(($netPay - $whole) * 100);
    $spell = function (int $num) use (&$spell): string {
        $ones = ['', 'One', 'Two', 'Three', 'Four', 'Five', 'Six', 'Seven', 'Eight', 'Nine', 'Ten',
            'Eleven', 'Twelve', 'Thirteen', 'Fourteen', 'Fifteen', 'Sixteen', 'Seventeen', 'Eighteen', 'Nineteen'];
        $tens = ['', '', 'Twenty', 'Thirty', 'Forty', 'Fifty', 'Sixty', 'Seventy', 'Eighty', 'Ninety'];
        if ($num === 0) {
            return 'Zero';
        }
        if ($num < 20) {
            return $ones[$num];
        }
        if ($num < 100) {
            return trim($tens[(int) floor($num / 10)] . ' ' . $ones[$num % 10]);
        }
        if ($num < 1000) {
            return trim($ones[(int) floor($num / 100)] . ' Hundred' . ($num % 100 ? ' ' . $spell($num % 100) : ''));
        }
        if ($num < 1000000) {
            return trim($spell((int) floor($num / 1000)) . ' Thousand' . ($num % 1000 ? ' ' . $spell($num % 1000) : ''));
        }

        return (string) $num;
    };
    $words = $spell($whole);
    if ($fraction > 0) {
        $words .= ' and ' . $spell($fraction) . ' Fils';
    }
    $netInWords = $currencyCode . ' ' . $words . ' Only';

    $bankName = optional($profile)->bank_name
        ?: (property_exists($payslip_settings, 'bank_name') ? $payslip_settings->bank_name : null)
        ?: '—';
    $iban = optional($profile)->iban
        ?: (property_exists($payslip_settings, 'iban') ? $payslip_settings->iban : null)
        ?: '—';
    $maxRows = max(count($earnings), count($deductions), 1);
@endphp
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Payslip</title>
    <style>
        * { box-sizing: border-box; }
        body {
            margin: 0;
            padding: 12px;
            font-family: DejaVu Sans, Arial, Helvetica, sans-serif;
            font-size: 11px;
            color: #111;
            line-height: 1.35;
        }
        .sheet {
            border: 1.5px solid #000;
            width: 100%;
            border-collapse: collapse;
        }
        .sheet td { vertical-align: top; }
        .pad { padding: 10px 12px; }
        .center { text-align: center; }
        .right { text-align: right; }
        .bold { font-weight: bold; }
        .muted { color: #333; }
        .header-logo {
            width: 170px;
            text-align: left;
            vertical-align: middle !important;
            padding-right: 8px;
        }
        .header-logo img {
            max-height: 72px;
            max-width: 160px;
            height: auto;
            width: auto;
        }
        .company-name {
            font-size: 15px;
            font-weight: bold;
            margin: 0 0 4px;
        }
        .company-address {
            font-size: 10px;
            margin: 0;
        }
        .month-title {
            font-size: 13px;
            font-weight: bold;
            margin: 10px 0 0;
        }
        .divider {
            border-top: 1px solid #000;
        }
        .info-table {
            width: 100%;
            border-collapse: collapse;
        }
        .info-table td {
            width: 50%;
            padding: 8px 12px;
            vertical-align: top;
        }
        .info-table .col-right {
            border-left: 1px solid #000;
        }
        .kv {
            width: 100%;
            border-collapse: collapse;
        }
        .kv td {
            padding: 3px 0;
            font-size: 11px;
        }
        .kv .label { width: 48%; text-align: left; }
        .kv .value { width: 52%; text-align: right; font-weight: normal; }
        .pay-table {
            width: 100%;
            border-collapse: collapse;
        }
        .pay-table > tbody > tr > td {
            width: 50%;
            padding: 8px 10px 12px;
            vertical-align: top;
        }
        .pay-table .col-right {
            border-left: 1px solid #000;
        }
        .inner {
            width: 100%;
            border-collapse: collapse;
        }
        .inner th {
            font-size: 11px;
            font-weight: bold;
            padding: 2px 4px 8px;
            border-bottom: 1px solid #000;
        }
        .inner td {
            font-size: 11px;
            padding: 4px;
            vertical-align: top;
        }
        .inner .totals td {
            font-weight: bold;
            padding-top: 10px;
            border-top: 1px solid #000;
        }
        .net-wrap {
            padding: 10px 12px 12px;
        }
        .net-label {
            font-size: 12px;
            font-weight: bold;
            margin: 0;
        }
        .net-amount {
            font-size: 14px;
            font-weight: bold;
            text-align: right;
            margin: 0;
        }
        .net-words {
            font-style: italic;
            font-size: 10px;
            margin: 6px 0 0;
        }
        .footer-note {
            text-align: center;
            font-size: 10px;
            margin-top: 10px;
            color: #222;
        }
        .currency-symbol {
            font-family: DejaVu Sans, sans-serif !important;
        }
    </style>
</head>
<body>
<table class="sheet">
    <tr>
        <td class="pad">
            <table style="width:100%; border-collapse:collapse;">
                <tr>
                    <td class="header-logo">
                        <img src="{{ $logoSrc }}" alt="logo">
                    </td>
                    <td class="center" style="vertical-align:middle;">
                        <p class="company-name">{{ $companyTitle }}</p>
                        @if($companyAddress)
                            <p class="company-address">{{ $companyAddress }}</p>
                        @endif
                        <p class="month-title">Payslip for the month of {{ $monthLabel }}</p>
                    </td>
                    <td style="width:170px;"></td>
                </tr>
            </table>
        </td>
    </tr>

    <tr>
        <td class="divider">
            <table class="info-table">
                <tr>
                    <td>
                        <table class="kv">
                            <tr>
                                <td class="label">Employee Name[Employee No]</td>
                                <td class="value">
                                    {{ $user->full_name }}@if($employeeNo) [{{ $employeeNo }}]@endif
                                </td>
                            </tr>
                            <tr>
                                <td class="label">Joining Date</td>
                                <td class="value">{{ $joiningDate }}</td>
                            </tr>
                            <tr>
                                <td class="label">Designation</td>
                                <td class="value">{{ $designation }}</td>
                            </tr>
                            <tr>
                                <td class="label">Location</td>
                                <td class="value">{{ $location ?: '—' }}</td>
                            </tr>
                        </table>
                    </td>
                    <td class="col-right">
                        <table class="kv">
                            <tr>
                                <td class="label">Bank</td>
                                <td class="value">{{ $bankName }}</td>
                            </tr>
                            <tr>
                                <td class="label">IBAN Number</td>
                                <td class="value">{{ $iban }}</td>
                            </tr>
                            <tr>
                                <td class="label">No of Working Days</td>
                                <td class="value">{{ $workingDays }}</td>
                            </tr>
                            <tr>
                                <td class="label">Unpaid Leave Days</td>
                                <td class="value">{{ $unpaidLeaveDays }}</td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </td>
    </tr>

    <tr>
        <td class="divider">
            <table class="pay-table">
                <tr>
                    <td>
                        <table class="inner">
                            <thead>
                            <tr>
                                <th style="width:46%; text-align:left;">Earnings</th>
                                <th style="width:27%; text-align:right;">Master</th>
                                <th style="width:27%; text-align:right;">Amount</th>
                            </tr>
                            </thead>
                            <tbody>
                            @for($i = 0; $i < $maxRows; $i++)
                                @php $row = $earnings[$i] ?? null; @endphp
                                <tr>
                                    <td>{{ $row['name'] ?? '' }}</td>
                                    <td class="right currency-symbol">{{ $row ? number_format($row['master'], 0, '.', '') : '' }}</td>
                                    <td class="right currency-symbol">{{ $row ? number_format($row['amount'], 0, '.', '') : '' }}</td>
                                </tr>
                            @endfor
                            <tr class="totals">
                                <td>Total Earnings</td>
                                <td class="right currency-symbol">{{ number_format($totalEarningsMaster, 0, '.', '') }}</td>
                                <td class="right currency-symbol">{{ number_format($totalEarningsAmount, 0, '.', '') }}</td>
                            </tr>
                            </tbody>
                        </table>
                    </td>
                    <td class="col-right">
                        <table class="inner">
                            <thead>
                            <tr>
                                <th style="width:65%; text-align:left;">Deductions</th>
                                <th style="width:35%; text-align:right;">Amount</th>
                            </tr>
                            </thead>
                            <tbody>
                            @for($i = 0; $i < $maxRows; $i++)
                                @php $row = $deductions[$i] ?? null; @endphp
                                <tr>
                                    <td>{{ $row['name'] ?? '' }}</td>
                                    <td class="right currency-symbol">{{ $row ? number_format($row['amount'], 0, '.', '') : '' }}</td>
                                </tr>
                            @endfor
                            <tr class="totals">
                                <td>Total Deductions</td>
                                <td class="right currency-symbol">{{ number_format($totalDeductionsAmount, 0, '.', '') }}</td>
                            </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
            </table>
        </td>
    </tr>

    <tr>
        <td class="divider net-wrap">
            <table style="width:100%; border-collapse:collapse;">
                <tr>
                    <td style="width:55%;">
                        <p class="net-label">Net Pay for the Month</p>
                        <p class="net-words">({{ $netInWords }})</p>
                    </td>
                    <td style="width:45%; vertical-align:middle;">
                        <p class="net-amount currency-symbol">{{ number_format($netPay, 0, '.', '') }}</p>
                    </td>
                </tr>
            </table>
            @if(property_exists($payslip_settings, 'note') && $payslip_settings->note)
                <p style="margin:10px 0 0; font-size:10px;"><b>Note:</b> {{ $payslip_settings->note }}</p>
            @endif
        </td>
    </tr>
</table>

<p class="footer-note">This is a system generated payslip and does not require signature.</p>
</body>
</html>
