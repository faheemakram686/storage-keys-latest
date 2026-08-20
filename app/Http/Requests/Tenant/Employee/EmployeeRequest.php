<?php

namespace App\Http\Requests\Tenant\Employee;

use App\Http\Requests\BaseRequest;

class EmployeeRequest extends BaseRequest
{

    public function rules()
    {
        $employee = $this->route()->parameter('employee');
        $employeeId = optional($employee)->id;

        return [
            'email' => [
                'required',
                'email',
                unique_active_user_email_rule($employeeId)
            ],
            'employee_id' => [
                'required',
                'min:2',
                unique_active_employee_id_rule($employeeId)
            ],
            'department_id' => 'required|integer',
            'designation_id' => 'required|integer',
            'employment_status_id' => 'required|integer',
            'work_shift_id' => 'nullable|integer',
            'gender' => 'required'
        ];
    }
}
