<?php

namespace App\Http\Controllers\Tenant\Employee;

use App\Http\Controllers\Controller;
use App\Models\Core\Auth\User;
use App\Models\Tenant\WorkingShift\WorkingShift;

class EmployeeJobHistoryController extends Controller
{
    public function index(User $employee): User
    {
        return $this->loadJobHistory($employee);
    }

    public function jobHistoryAPI(User $employee): User
    {
        return $this->loadJobHistory($employee);
    }

    protected function loadJobHistory(User $employee): User
    {
        $employee->load([
            'departments' => fn ($b) => $b->orderBy('pivot_start_date', 'DESC')->select('id', 'name'),
            'workingShifts' => fn ($b) => $b->orderBy('pivot_start_date', 'DESC')->select('id', 'name'),
            'designations' => fn ($b) => $b->orderBy('pivot_start_date', 'DESC')->select('id', 'name'),
            'employmentStatuses' => fn ($b) => $b->orderBy('pivot_start_date', 'DESC')->select('id', 'name'),
            'roles:id,name,alias',
            'upcomingWorkingShift',
            'upcomingWorkingShift.workingShift:id,name',
            'profile:id,user_id,joining_date',
        ]);

        // Match employee header: if no assigned/upcoming shift history, show the
        // effective default work shift instead of "Not yet added".
        if ($employee->workingShifts->isEmpty() && $employee->upcomingWorkingShift->isEmpty()) {
            $fallback = WorkingShift::getDefault(['id', 'name']);
            if ($fallback) {
                $start = optional($employee->profile)->joining_date
                    ?: todayFromApp()->toDateString();
                $fallback->setAttribute('pivot', (object) [
                    'start_date' => $start,
                    'end_date' => null,
                    'user_id' => $employee->id,
                    'working_shift_id' => $fallback->id,
                ]);
                $employee->setRelation('workingShifts', collect([$fallback]));
            }
        }

        return $employee;
    }
}
