<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Tenant\Payroll\Payslip;
use Illuminate\Support\Facades\Auth;

$payslip = Payslip::with([
    'user.profile',
    'user.designation',
    'user.department',
    'beneficiaries.beneficiary',
    'payrun',
])->latest('id')->first();

if (!$payslip) {
    echo "no-payslip\n";
    exit(1);
}

// Enrich sample payrun settings for classic layout demo
$data = json_decode($payslip->payrun->data ?: '{}', true) ?: [];
$data['title'] = $data['title'] ?? 'Mufateeh Al Makhazan Stores Management LLC';
$data['address'] = $data['address'] ?? 'Plot No 4202, Emirates Industrial City, Near Al Sajja Police Station, Sharjah, United Arab Emirates, 71161.';
$data['bank_name'] = $data['bank_name'] ?? 'Mashreqbank';
$data['iban'] = $data['iban'] ?? 'AE740330000019101556692';
$payslip->payrun->data = json_encode($data);
$payslip->payrun->save();

if ($payslip->user && $payslip->user->profile) {
    $payslip->user->profile->forceFill([
        'employee_id' => $payslip->user->profile->employee_id ?: 'E0012',
        'joining_date' => $payslip->user->profile->joining_date ?: '2024-10-14',
        'res_visa_loc' => $payslip->user->profile->res_visa_loc ?: 'Dubai',
    ])->save();
}

Auth::loginUsingId($payslip->user_id);

$controller = app(App\Http\Controllers\Tenant\Payroll\PayslipController::class);
$method = new ReflectionMethod($controller, 'payslipViewData');
$method->setAccessible(true);
$viewData = $method->invoke($controller, $payslip);

$html = view('tenant.payroll.pdf.payslip', $viewData)->render();
$htmlPath = public_path('sample-payslip.html');
file_put_contents($htmlPath, $html);

$pdf = Barryvdh\DomPDF\Facade\Pdf::loadView('tenant.payroll.pdf.payslip', $viewData);
$pdfPath = public_path('sample-payslip.pdf');
file_put_contents($pdfPath, $pdf->output());

echo "html={$htmlPath}\n";
echo "pdf={$pdfPath}\n";
echo "payslip_id={$payslip->id}\n";
echo 'html_bytes=' . strlen($html) . "\n";
echo 'pdf_bytes=' . filesize($pdfPath) . "\n";
