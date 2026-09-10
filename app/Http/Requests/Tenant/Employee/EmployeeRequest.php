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
     * Unique against other users only. Prefer profile PK ignore; fall back to
     * an explicit user_id exclusion so own ID is never treated as a duplicate.
     */
    protected function uniqueEmployeeIdRule(?int $ignoreUserId)
    {
        return function ($attribute, $value, $fail) use ($ignoreUserId) {
            $query = DB::table('profiles')->where('employee_id', $value);

            if (!empty($ignoreUserId)) {
                $query->where('user_id', '!=', $ignoreUserId);
            }

            if ($query->exists()) {
                $fail(__('validation.unique', ['attribute' => str_replace('_', ' ', $attribute)]));
            }
        };
    }

    /**
     * On update, ignore the current employee so their own email / employee_id
     * are not treated as duplicates.
     */
    protected function employeeIdToIgnore()
    {
        // Create / invite must not ignore anyone.
        if ($this->isMethod('post')) {
            return null;
        }

        $employee = $this->route('employee');

        if (is_object($employee) && !empty($employee->id)) {
            return (int) $employee->id;
        }

        if (is_numeric($employee)) {
            return (int) $employee;
        }

        // Fallback when route model is missing but payload has user id (edit form).
        if ($this->filled('id') && is_numeric($this->input('id'))) {
            return (int) $this->input('id');
        }

        return null;
    }
}
