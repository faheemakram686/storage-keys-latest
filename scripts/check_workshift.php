<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Core\Auth\User;
use App\Models\Tenant\WorkingShift\WorkingShift;
use App\Models\Tenant\WorkingShift\WorkingShiftUser;
use App\Models\Tenant\WorkingShift\UpcomingUserWorkingShift;
use Illuminate\Support\Facades\DB;

$user = User::query()
    ->whereHas('profile', fn ($q) => $q->where('employee_id', 'E0001'))
    ->orWhere(DB::raw("CONCAT(first_name,' ',last_name)"), 'like', '%Hamza%')
    ->with(['workingShift', 'workingShifts', 'upcomingWorkingShift.workingShift', 'department'])
    ->first();

if (!$user) {
    $user = User::query()->where('is_in_employee', 1)->with(['workingShift', 'workingShifts', 'upcomingWorkingShift', 'profile'])->latest('id')->first();
}

echo "user_id={$user->id} name={$user->full_name}\n";
echo "employee_id=" . optional($user->profile)->employee_id . "\n";
echo "current workingShift=" . optional($user->workingShift)->name . " (id=" . optional($user->workingShift)->id . ")\n";
echo "workingShifts count=" . $user->workingShifts->count() . "\n";
foreach ($user->workingShifts as $ws) {
    echo "  - {$ws->name} start={$ws->pivot->start_date} end={$ws->pivot->end_date}\n";
}
echo "upcoming count=" . $user->upcomingWorkingShift->count() . "\n";
foreach ($user->upcomingWorkingShift as $up) {
    echo "  - shift=" . optional($up->workingShift)->name . " start={$up->start_date}\n";
}

echo "\npivot rows in working_shift_user:\n";
foreach (WorkingShiftUser::where('user_id', $user->id)->get() as $row) {
    echo "  ws={$row->working_shift_id} start={$row->start_date} end={$row->end_date}\n";
}

echo "\nupcoming_user_working_shifts:\n";
foreach (UpcomingUserWorkingShift::where('user_id', $user->id)->get() as $row) {
    echo "  ws={$row->working_shift_id} start={$row->start_date}\n";
}

echo "\ndefault=" . WorkingShift::getDefault()->name . " id=" . WorkingShift::getDefault()->id . "\n";
