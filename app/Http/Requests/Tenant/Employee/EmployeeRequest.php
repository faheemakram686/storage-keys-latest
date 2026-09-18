<?php

namespace App\Http\Requests\Tenant\Employee;

use App\Http\Requests\BaseRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class EmployeeRequest extends BaseRequest
{
    protected function prepareForValidation()
    {
        $merge = [];

        if ($this->filled('gender') && is_string($this->gender)) {
            $merge['gender'] = strtolower(trim($this->gender));
        }

        if ($this->filled('employee_id') && is_string($this->employee_id)) {
            $merge['employee_id'] = trim($this->employee_id);
        }

        if ($merge) {
            $this->merge($merge);
        }
    }

    public function rules()
    {
        $ignoreUserId = $this->employeeIdToIgnore();

        $emailRule = Rule::unique('users', 'email')->whereNull('deleted_at');

        if (!empty($ignoreUserId)) {
            $emailRule->ignore($ignoreUserId);
        }

        return [
            'email' => [
                'required',
                'email',
                $emailRule,
            ],
            'employee_id' => [
                'required',
                'min:2',
                $this->uniqueEmployeeIdRule($ignoreUserId),
            ],
            'department_id' => 'required|integer',
            'designation_id' => 'required|integer',
            'employment_status_id' => 'required|integer',
            'work_shift_id' => 'nullable|integer',
            'gender' => 'nullable|in:male,female,other',
        ];
    }

    /**
     * Unique against other non-deleted users only — never treat the current
     * user's own employee_id as a duplicate when editing.
     */
    protected function uniqueEmployeeIdRule(?int $ignoreUserId)
    {
        return function ($attribute, $value, $fail) use ($ignoreUserId) {
            $value = trim((string) $value);
            if ($value === '') {
                return;
            }

            $query = DB::table('profiles')
                ->join('users', 'users.id', '=', 'profiles.user_id')
                ->whereNull('users.deleted_at')
                ->whereRaw('LOWER(TRIM(profiles.employee_id)) = ?', [mb_strtolower($value)]);

            if (!empty($ignoreUserId)) {
                $query->where('profiles.user_id', '!=', (int) $ignoreUserId);
            }

            if ($query->exists()) {
                $fail(__('validation.unique', ['attribute' => 'employee id']));
            }
        };
    }

    /**
     * On update, ignore the current employee so their own email / employee_id
     * are not treated as duplicates.
     */
    protected function employeeIdToIgnore()
    {
        $method = strtolower($this->method());
        $spoof = strtolower((string) $this->input('_method', ''));

        // Pure create (POST, not method-spoofed update).
        if ($method === 'post' && !in_array($spoof, ['put', 'patch'], true)) {
            return null;
        }

        $employee = $this->route('employee');

        if (is_object($employee) && !empty($employee->id)) {
            return (int) $employee->id;
        }

        if (is_numeric($employee)) {
            return (int) $employee;
        }

        if ($this->filled('id') && is_numeric($this->input('id'))) {
            return (int) $this->input('id');
        }

        if ($this->filled('user_id') && is_numeric($this->input('user_id'))) {
            return (int) $this->input('user_id');
        }

        return null;
    }
}
