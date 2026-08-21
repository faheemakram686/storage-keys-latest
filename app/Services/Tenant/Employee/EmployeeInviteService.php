<?php


namespace App\Services\Tenant\Employee;


use App\Helpers\Core\Traits\HasWhen;
use App\Mail\Tenant\EmployeeCreateMail;
use App\Mail\Tenant\EmployeeInvitationMail;
use App\Models\Core\Auth\User;
use App\Repositories\Core\Status\StatusRepository;
use App\Services\Core\Auth\UserInvitationService;
use App\Services\Settings\SettingService;
use App\Services\Tenant\TenantService;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class EmployeeInviteService extends TenantService
{
    use HasWhen;

    protected EmployeeService $service;

    protected UserInvitationService $userService;

    public function __construct(EmployeeService $service, UserInvitationService $userService)
    {
        $this->service = $service;
        $this->userService = $userService;
    }

    public function validateMailSettings()
    {
        throw_if(
            !count(resolve(SettingService::class)->getCachedMailSettings()),
            ValidationException::withMessages([
                'email' => [__t('no_delivery_settings_found')]
            ])
        );

        return $this;
    }

    public function invite()
    {
        /**@var User $user*/
        $user = $this->userService
            ->create($this->getAttribute('email'), ['is_in_employee' => isset($this->attributes['is_in_employee']) ? $this->attributes['is_in_employee'] : 1])
            ->assignRoles($this->getAttr('roles') ?: [])
            ->getModel();

        $this->service
            ->setModel($user)
            ->setAttributes($this->getAttributes())
            ->saveEmployeeId()
            ->saveJoiningDate()
            ->saveSalary()
            ->setIsInvite(true)
            ->assignToDepartment()
            ->assignToDesignation()
            ->assignEmploymentStatus();

        $user->load([
            'department:id,name',
            'designation:id,name',
            'profile:id,user_id,joining_date,employee_id',
            'workingShift:id,name',
            'employmentStatus:id,name,class',
            'roles:id,name'
        ]);

        $this->inviteEmployee($user);

        return $user;

    }

    public function create()
    {
        $password = Str::random(8);
        /**@var User $user */
        $user = $this->userService
            ->create(
                $this->getAttribute('email'),
                array_merge(
                    $this->getAttributes(['first_name', 'last_name']),
                    [
                        'is_in_employee' => isset($this->attributes['is_in_employee']) ? $this->attributes['is_in_employee'] : 1,
                        'status_id' => resolve(StatusRepository::class)->userActive(),
                        'invitation_token' => '',
                        'password' => Hash::make($password)
                    ]
                ),
                true
            )
            ->assignRoles($this->getAttr('roles') ?: [])
            ->getModel();

        $this->service
            ->setModel($user)
            ->setAttributes($this->getAttributes())
            ->saveEmployeeId()
            ->saveJoiningDate()
            ->saveSalary()
            ->setIsInvite(false)
            ->assignToDepartment()
            ->assignToDesignation()
            ->assignEmploymentStatus();

        $user->load([
            'department:id,name',
            'designation:id,name',
            'profile:id,user_id,joining_date,employee_id',
            'workingShift:id,name',
            'employmentStatus:id,name,class',
            'roles:id,name'
        ]);

        $user->tempPass= $password;
        $this->createEmployeeMail($user);

        return $user;

    }

    public function inviteEmployee(User $user)
    {
        Mail::to($user)
            ->locale(session()->get('locale') ?: settings('language') ?: "en")
            ->send((new EmployeeInvitationMail($user))->onQueue('high')->delay(5));

        return $this;
    }

    public function createEmployeeMail(User $user)
    {
        Mail::to($user)
            ->locale(session()->get('locale') ?: settings('language') ?: "en")
            ->send((new EmployeeCreateMail($user))->onQueue('high')->delay(5));

        return $this;
    }

    public function cancel()
    {
        /**@var User $user*/
        $user = $this->model;

        $user->departments()->sync([]);

        $user->upcomingWorkingShift()->delete();

        $user->designations()->sync([]);

        $user->employmentStatuses()->sync([]);

        $user->workingShifts()->sync([]);

        $user->salaries()->delete();

        $user->profile()->delete();

        $user->assignedLeaves()->delete();

        $this->userService
            ->setModel($user)
            ->detachRoles()
            ->delete();

        return $user;
    }

    public function validateRoles(): self
    {
        $roles = $this->getAttr('roles');
        if (!is_array($roles)) {
            $roles = $roles ? [$roles] : [];
        }

        // Single-role policy: only the first role id is kept.
        $roles = array_values(array_filter($roles));
        if (count($roles) > 1) {
            $roles = [reset($roles)];
            $this->setAttr('roles', $roles);
        }

        $sync = resolve(\App\Services\Core\Auth\UserRoleSyncService::class);
        $appTypeId = optional(\App\Models\Core\Auth\Type::findByAlias('app'))->id;

        validator(
            ['roles' => $roles],
            [
                'roles' => ['required', 'array', 'min:1', 'max:1'],
                'roles.*' => [
                    'required',
                    'integer',
                    Rule::exists('roles', 'id')->where(function ($query) use ($appTypeId) {
                        $query->where(function ($q) {
                            $q->where('is_admin', 0)->orWhereNull('is_admin');
                        });
                        if ($appTypeId) {
                            $query->where('type_id', '!=', $appTypeId);
                        }
                    }),
                ],
            ],
            [
                'roles.*.exists' => __('Only staff roles can be assigned. App Admin cannot be assigned.'),
            ]
        )->validate();

        // Ensure resolved roles are staff (covers edge type mismatches)
        foreach ($roles as $roleId) {
            $role = \App\Models\Core\Auth\Role::with('type')->find($roleId);
            if (!$role || !$sync->isStaffRole($role)) {
                throw ValidationException::withMessages([
                    'roles' => [__('Only staff roles can be assigned. App Admin cannot be assigned.')],
                ]);
            }
        }

        return $this;
    }

}