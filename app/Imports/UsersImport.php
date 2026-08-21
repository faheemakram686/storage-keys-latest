<?php

namespace App\Imports;


use App\Helpers\Traits\MakeArrayFromString;
use App\Helpers\Traits\NameSplitTrait;
use App\Models\Core\Auth\Role;
use App\Models\Tenant\Employee\Department;
use App\Models\Tenant\Employee\Designation;
use App\Models\Tenant\Employee\EmploymentStatus;
use App\Rules\RoleExistRule;
use App\Services\Core\Auth\UserRoleSyncService;
use App\Services\Tenant\Import\EmployeeImportService;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class UsersImport implements ToModel, WithHeadingRow, WithValidation, WithBatchInserts, WithChunkReading, SkipsOnFailure
{
    use Importable, SkipsFailures, MakeArrayFromString, NameSplitTrait;

    public function model(array $row)
    {
        $department = Department::query()->where('name', $row['department'])->first()->id;
        $designation = Designation::query()->where('name', $row['designation'])->first()->id;
        $employment_status = EmploymentStatus::query()->where('name', $row['employment_status'])->first()->id;

        $roleName = $this->resolveRoleName($row);
        $sync = resolve(UserRoleSyncService::class);
        $role = Role::query()
            ->with('type')
            ->where('name', $roleName)
            ->get()
            ->first(fn (Role $r) => $sync->isStaffRole($r));

        $roles = $role ? [$role->id] : [];
        [$first_name, $last_name] = array_values($this->getFirstnameLastnameFromName($row['name']));

        DB::transaction(fn() => resolve(EmployeeImportService::class)
            ->setAttrs(array_merge($row, [
                'department_id' => $department,
                'designation_id' => $designation,
                'employment_status_id' => $employment_status,
                'roles' => $roles,
                'first_name' => $first_name,
                'last_name' => $last_name,
            ]))->saveEmployee()
            ->sendPasswordResetMail()
        );
    }

    /**
     * Prefer singular `role` column; fall back to legacy `roles` (first name only).
     */
    protected function resolveRoleName(array $row): string
    {
        if (!empty($row['role'])) {
            return trim((string) $row['role']);
        }

        if (!empty($row['roles'])) {
            $parts = array_values(array_filter(array_map('trim', $this->makeArray($row['roles']))));

            return $parts[0] ?? '';
        }

        return '';
    }

    public array $requiredHeading = [
        "name",
        "email",
        "gender",
        "employee_id",
        "department",
        "designation",
        "employment_status",
        "role",
        "salary",
        "joining_date",
    ];

    public function rules(): array
    {
        return [
            '*.name' => ['required', 'string'],
            '*.email' => [
                'required',
                'email',
                'distinct',
                unique_active_user_email_rule(),
            ],
            '*.gender' => ['required', 'string', Rule::in(['male', 'female', 'other', 'Male', 'Female', 'Other'])],
            '*.employee_id' => ['required', 'distinct', unique_active_employee_id_rule()],
            '*.department' => ['required', 'string', 'exists:departments,name'],
            '*.designation' => ['required', 'string', 'exists:designations,name'],
            '*.employment_status' => ['required', 'string', 'exists:employment_statuses,name'],
            '*.role' => ['required', 'string', new RoleExistRule],
            '*.salary' => ['nullable', 'numeric'],
            '*.joining_date' => ['nullable', 'date'],
        ];
    }

    public function batchSize(): int
    {
        return 100;
    }

    public function chunkSize(): int
    {
        return 100;
    }
}
