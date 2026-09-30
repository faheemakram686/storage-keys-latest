<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Core\Auth\User;
use App\Models\Tenant\WorkingShift\WorkingShift;
use App\Services\Tenant\Employee\EmployeeUpdateService;
use App\Models\Tenant\WorkingShift\WorkingShiftUser;
use Illuminate\Support\Facades\Auth;

$user = User::query()->where('is_in_employee', 1)->first() ?: User::query()->first();
$shift = WorkingShift::getDefault();

Auth::loginUsingId($user->id);

echo "before pivots=" . WorkingShiftUser::where('user_id', $user->id)->whereNull('end_date')->count() . "\n";

$service = resolve(EmployeeUpdateService::class);
$service
    ->setMethod('workshift')
    ->validateMethod()
    ->setModel($user)
    ->setAttributes(['work_shift_id' => $shift->id])
    ->callMethod();

echo "after pivots=" . WorkingShiftUser::where('user_id', $user->id)->whereNull('end_date')->count() . "\n";
foreach (WorkingShiftUser::where('user_id', $user->id)->get() as $row) {
    echo "ws={$row->working_shift_id} start={$row->start_date} end={$row->end_date}\n";
}

$ctrl = app(App\Http\Controllers\Tenant\Employee\EmployeeJobHistoryController::class);
$history = $ctrl->index($user->fresh());
echo "history workingShifts=" . $history->workingShifts->count() . "\n";
if ($history->workingShifts->count()) {
    $ws = $history->workingShifts->first();
    echo "name={$ws->name} start=" . ($ws->pivot->start_date ?? $ws->pivot['start_date'] ?? 'n/a') . "\n";
}
