<?php

namespace Tests\Feature;

use App\Models\Core\Auth\Role;
use App\Models\Core\Auth\Type;
use App\Models\Core\Auth\User;
use App\Models\Core\Status;
use App\Models\Tenant\Employee\Department;
use App\Models\Tenant\Employee\Designation;
use App\Models\Tenant\Employee\EmploymentStatus;
use App\Models\Tenant\WorkingShift\WorkingShift;
use App\Services\Core\Auth\UserInvitationService;
use App\Services\Tenant\Employee\EmployeeInviteService;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Tests\CreatesApplication;

class EmployeeInviteFlowTest extends BaseTestCase
{
    use CreatesApplication;
    use DatabaseTransactions;

    /** @test */
    public function assign_roles_accepts_wrapped_get_attributes_shape()
    {
        $user = $this->makeUser();
        $role = $this->makeTenantRole();

        $service = resolve(UserInvitationService::class)->setModel($user);

        // Regression: EmployeeInviteService used getAttributes('roles') which wraps as ['roles' => [id]]
        $service->assignRoles(['roles' => [$role->id]]);

        $user->refresh()->load('roles');
        $this->assertTrue($user->roles->contains('id', $role->id), 'Wrapped roles payload must still assign the HRM role');
    }

    /** @test */
    public function assign_roles_accepts_plain_id_list()
    {
        $user = $this->makeUser();
        $role = $this->makeTenantRole();

        resolve(UserInvitationService::class)
            ->setModel($user)
            ->assignRoles([$role->id]);

        $user->refresh()->load('roles');
        $this->assertTrue($user->roles->contains('id', $role->id));
    }

    /** @test */
    public function invite_creates_employee_with_invited_status_profile_and_role()
    {
        $invitedStatus = Status::findByNameAndType('status_invited', 'user');
        if (!$invitedStatus) {
            $this->markTestSkipped('status_invited is missing — seed statuses first');
        }

        $department = Department::query()->first();
        $designation = Designation::query()->first();
        $employmentStatus = EmploymentStatus::query()->where('alias', '!=', 'terminated')->first()
            ?: EmploymentStatus::query()->first();
        $workingShift = WorkingShift::query()->where('is_default', 1)->first()
            ?: WorkingShift::query()->first();

        if (!$department || !$designation || !$employmentStatus) {
            $this->markTestSkipped('Employee master data (department / designation / employment status) is missing');
        }

        if ($workingShift && !(int) $workingShift->is_default) {
            $workingShift->is_default = 1;
            $workingShift->save();
        }

        $role = $this->makeTenantRole();
        $actor = $this->makeUser();
        $this->actingAs($actor);

        Mail::fake();
        cache()->put('app-delivery-settings', ['mailer' => 'array'], 60);

        $email = 'invite' . uniqid() . '@example.com';
        $employeeId = 'INV' . substr(uniqid(), -5);

        $user = resolve(EmployeeInviteService::class)
            ->setAttributes([
                'email' => $email,
                'roles' => [$role->id],
                'employee_id' => $employeeId,
                'gender' => 'male',
                'department_id' => $department->id,
                'designation_id' => $designation->id,
                'employment_status_id' => $employmentStatus->id,
                'salary' => 1000,
                'joining_date' => now()->format('Y-m-d'),
                'is_in_employee' => 1,
            ])
            ->invite();

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'email' => $email,
            'status_id' => $invitedStatus->id,
            'is_in_employee' => 1,
        ]);
        $this->assertNotEmpty($user->invitation_token);

        $this->assertDatabaseHas('profiles', [
            'user_id' => $user->id,
            'employee_id' => $employeeId,
            'gender' => 'male',
        ]);

        $user->load('roles');
        $this->assertTrue($user->roles->contains('id', $role->id), 'Invited employee must receive the selected HRM role');
        $this->assertDatabaseHas('department_user', ['user_id' => $user->id, 'department_id' => $department->id]);
        $this->assertDatabaseHas('designation_user', ['user_id' => $user->id, 'designation_id' => $designation->id]);
    }

    protected function makeUser(): User
    {
        $statusId = optional(Status::query()->first())->id ?? 1;

        return User::create([
            'first_name' => 'Invite',
            'last_name' => 'Actor',
            'email' => 'actor' . uniqid() . '@example.com',
            'password' => Hash::make('password'),
            'status_id' => $statusId,
            'is_in_employee' => 1,
        ]);
    }

    protected function makeTenantRole(): Role
    {
        $type = Type::findByAlias('tenant') ?: Type::create(['name' => 'Tenant', 'alias' => 'tenant']);

        return Role::create([
            'name' => 'Invite Role ' . uniqid(),
            'alias' => 'invite_role_' . Str::random(6),
            'type_id' => $type->id,
            'is_admin' => 0,
            'is_default' => 0,
        ]);
    }
}
