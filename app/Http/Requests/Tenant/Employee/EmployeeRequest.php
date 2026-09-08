<?php

namespace App\Http\Requests\Tenant\Employee;

use App\Http\Requests\BaseRequest;
use Illuminate\Validation\Rule;

class EmployeeRequest extends BaseRequest
{
    protected function prepareForValidation()
    {
        if ($this->filled('gender') && is_string($this->gender)) {
            $this->merge([
                'gender' => strtolower(trim($this->gender)),
            ]);
        }
    }

    public function rules()
    {
        $ignoreUserId = $this->employeeIdToIgnore();

        $emailRule = Rule::unique('users', 'email')->whereNull('deleted_at');
        $employeeIdRule = Rule::unique('profiles', 'employee_id');

        if (!empty($ignoreUserId)) {
            $emailRule->ignore($ignoreUserId);
            $employeeIdRule->ignore($ignoreUserId, 'user_id');
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
                $employeeIdRule,
            ],
            'department_id' => 'required|integer',
            'designation_id' => 'required|integer',
            'employment_status_id' => 'required|integer',
            'work_shift_id' => 'nullable|integer',
            'gender' => 'nullable|in:male,female,other',
        ];
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
