<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Core\Auth\User;
use App\Models\Tenant\Payroll\Payslip;
use App\Models\Tenant\Payroll\Payrun;
use App\Models\Tenant\Payroll\Beneficiary;
use App\Models\Tenant\Payroll\BeneficiaryValue;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

echo "payslips=" . Payslip::count() . PHP_EOL;
echo "users=" . User::count() . PHP_EOL;
echo "payruns=" . Payrun::count() . PHP_EOL;

$payslip = Payslip::with([
    'user.profile',
    'user.designation',
    'user.department',
    'beneficiaries.beneficiary',
    'payrun',
])->latest('id')->first();

if (!$payslip) {
    echo "Creating sample payslip..." . PHP_EOL;

    $user = User::with(['profile', 'designation', 'department'])->whereHas('profile')->first()
        ?: User::with(['profile', 'designation', 'department'])->first();

    if (!$user) {
        echo "ERR: no users found\n";
        exit(1);
    }

    // Ensure profile basics for layout demo
    if ($user->profile) {
        $user->profile->forceFill([
            'employee_id' => $user->profile->employee_id ?: 'E0012',
            'joining_date' => $user->profile->joining_date ?: '2024-10-14',
            'res_visa_loc' => $user->profile->res_visa_loc ?: 'Dubai',
        ])->save();
    }

    $statusId = DB::table('statuses')->where('name', 'like', '%paid%')->orWhere('name', 'like', '%generated%')->value('id')
        ?: DB::table('statuses')->value('id')
        ?: 1;

    $start = Carbon::parse('2026-06-01');
    $end = Carbon::parse('2026-06-30');

    $payrunData = json_encode([
        'title' => 'Mufateeh Al Makhazan Stores Management LLC',
        'address' => 'Plot No 4202, Emirates Industrial City, Near Al Sajja Police Station, Sharjah, United Arab Emirates, 71161.',
        'logo' => null,
        'note' => null,
    ]);

    DB::beginTransaction();
    try {
        $payrun = Payrun::create([
            'name' => 'Sample Jun 2026',
            'data' => $payrunData,
            'status_id' => $statusId,
            'executed_by' => $user->id,
        ]);

        // Fill required payrun columns if they exist
        $payrunAttrs = [];
        foreach (['period' => 'monthly', 'consider_type' => 'none', 'consider_overtime' => 0] as $col => $val) {
            if (Schema::hasColumn('payruns', $col)) {
                $payrunAttrs[$col] = $val;
            }
        }
        if ($payrunAttrs) {
            $payrun->forceFill($payrunAttrs)->save();
        }

        $payslip = Payslip::create([
            'user_id' => $user->id,
            'status_id' => $statusId,
            'start_date' => $start->toDateString(),
            'end_date' => $end->toDateString(),
            'period' => 'monthly',
            'net_salary' => 2290,
            'consider_type' => 'none',
            'consider_overtime' => 0,
            'payrun_id' => $payrun->id,
            'basic_salary' => 1000,
        ]);

        $makeBeneficiary = function (string $name, string $type) {
            $b = Beneficiary::query()->where('name', $name)->where('type', $type)->first();
            if (!$b) {
                $attrs = ['name' => $name, 'type' => $type];
                if (Schema::hasColumn('beneficiaries', 'is_active')) {
                    $attrs['is_active'] = 1;
                }
                $b = Beneficiary::create($attrs);
            }
            return $b;
        };

        $allowances = [
            ['HOUSING ALLOWANCES', 200],
            ['TRANSPORT', 500],
            ['OTHER ALLOWANCE', 800],
        ];
        foreach ($allowances as [$name, $amount]) {
            $b = $makeBeneficiary($name, 'allowance');
            BeneficiaryValue::create([
                'beneficiary_id' => $b->id,
                'amount' => $amount,
                'is_percentage' => 0,
                'beneficiary_valuable_type' => Payslip::class,
                'beneficiary_valuable_id' => $payslip->id,
            ]);
        }

        $ded = $makeBeneficiary('TRAFFIC FINE DEDUCTION', 'deduction');
        BeneficiaryValue::create([
            'beneficiary_id' => $ded->id,
            'amount' => 210,
            'is_percentage' => 0,
            'beneficiary_valuable_type' => Payslip::class,
            'beneficiary_valuable_id' => $payslip->id,
        ]);

        DB::commit();
        echo "created_payslip_id={$payslip->id}\n";
    } catch (Throwable $e) {
        DB::rollBack();
        echo 'ERR_CREATE=' . $e->getMessage() . "\n" . $e->getFile() . ':' . $e->getLine() . "\n";
        exit(1);
    }

    $payslip->load([
        'user.profile',
        'user.designation',
        'user.department',
        'beneficiaries.beneficiary',
        'payrun',
    ]);
}

$beneficiaries = count($payslip->beneficiaries)
    ? $payslip->beneficiaries
    : ($payslip->without_beneficiary ? collect() : optional($payslip->payrun)->beneficiaries);

$settings = (object) [
    'tenant_name' => 'Mufateeh Al Makhazan Stores Management LLC',
    'address' => 'Plot No 4202, Emirates Industrial City, Near Al Sajja Police Station, Sharjah',
    'area' => null,
    'city' => 'United Arab Emirates',
    'zip_code' => '71161',
    'country' => null,
    'tenant_logo' => null,
    'currency_symbol' => 'AED',
    'currency_position' => 'prefix',
    'currency_code' => 'AED',
];

$payslipSettings = (object) json_decode(optional($payslip->payrun)->data ?: '{}');
if (empty($payslipSettings->title)) {
    $payslipSettings->title = $settings->tenant_name;
}
if (empty($payslipSettings->address)) {
    $payslipSettings->address = 'Plot No 4202, Emirates Industrial City, Near Al Sajja Police Station, Sharjah, United Arab Emirates, 71161.';
}

$salaryAmount = $payslip->basic_salary;
$totalAllowance = 0;
$totalDeduction = 0;
foreach ($beneficiaries as $beneficiary) {
    $type = optional($beneficiary->beneficiary)->type;
    $amt = $beneficiary->is_percentage == 1
        ? round(($salaryAmount / 100) * $beneficiary->amount, 2)
        : (float) $beneficiary->amount;
    if ($type === 'allowance') {
        $totalAllowance += $amt;
    }
    if ($type === 'deduction') {
        $totalDeduction += $amt;
    }
}

try {
    $pdf = Pdf::loadView('tenant.payroll.pdf.payslip', [
        'payslip' => $payslip,
        'beneficiaries' => $beneficiaries,
        'totalAllowance' => $totalAllowance,
        'totalDeduction' => $totalDeduction,
        'settings' => $settings,
        'salaryAmount' => $salaryAmount,
        'payslipFor' => 'Jun 2026',
        'payslip_settings' => $payslipSettings,
    ]);

    $dir = storage_path('app/public');
    if (!is_dir($dir)) {
        mkdir($dir, 0777, true);
    }
    $out = $dir . '/sample-payslip.pdf';
    file_put_contents($out, $pdf->output());
    echo "PDF_OK={$out}\n";
    echo 'bytes=' . filesize($out) . PHP_EOL;
    echo 'payslip_id=' . $payslip->id . PHP_EOL;
    echo 'user=' . optional($payslip->user)->full_name . PHP_EOL;
} catch (Throwable $e) {
    echo 'ERR_PDF=' . $e->getMessage() . "\n" . $e->getFile() . ':' . $e->getLine() . "\n";
    exit(1);
}
