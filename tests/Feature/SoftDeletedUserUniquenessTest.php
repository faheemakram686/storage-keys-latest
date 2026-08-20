<?php

namespace Tests\Feature;

use App\Models\Core\Auth\User;
use App\Models\Tenant\Employee\Profile;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Tests\CreatesApplication;

class SoftDeletedUserUniquenessTest extends BaseTestCase
{
    use CreatesApplication;
    use DatabaseTransactions;

    /** @test */
    public function soft_deleted_email_and_employee_id_can_be_reused()
    {
        $email = 'reuse' . uniqid() . '@example.com';
        $employeeId = 'E' . substr(uniqid(), -4);

        $user = User::create([
            'first_name' => 'Deleted',
            'last_name' => 'User',
            'email' => $email,
            'password' => Hash::make('password'),
            'status_id' => 1,
            'is_in_employee' => 1,
        ]);

        Profile::create([
            'user_id' => $user->id,
            'employee_id' => $employeeId,
            'gender' => 'male',
        ]);

        $user->is_deleted = 1;
        $user->save();
        $user->delete();

        $this->assertNotNull($user->fresh()->deleted_at);
        $this->assertDatabaseHas('profiles', [
            'user_id' => $user->id,
            'employee_id' => $employeeId,
        ]);

        $emailValidator = Validator::make(
            ['email' => $email],
            ['email' => ['required', 'email', unique_active_user_email_rule()]]
        );
        $this->assertFalse($emailValidator->fails(), 'Soft-deleted email should pass uniqueness validation');

        $employeeIdValidator = Validator::make(
            ['employee_id' => $employeeId],
            ['employee_id' => ['required', unique_active_employee_id_rule()]]
        );
        $this->assertFalse($employeeIdValidator->fails(), 'Soft-deleted employee_id should pass uniqueness validation');

        $replacement = User::create([
            'first_name' => 'New',
            'last_name' => 'User',
            'email' => $email,
            'password' => Hash::make('password'),
            'status_id' => 1,
            'is_in_employee' => 1,
        ]);

        Profile::create([
            'user_id' => $replacement->id,
            'employee_id' => $employeeId,
            'gender' => 'male',
        ]);

        $this->assertDatabaseHas('users', [
            'id' => $replacement->id,
            'email' => $email,
        ]);
        $this->assertDatabaseHas('profiles', [
            'user_id' => $replacement->id,
            'employee_id' => $employeeId,
        ]);
    }

    /** @test */
    public function active_email_and_employee_id_remain_unique()
    {
        $email = 'active' . uniqid() . '@example.com';
        $employeeId = 'A' . substr(uniqid(), -4);

        $user = User::create([
            'first_name' => 'Active',
            'last_name' => 'User',
            'email' => $email,
            'password' => Hash::make('password'),
            'status_id' => 1,
            'is_in_employee' => 1,
        ]);

        Profile::create([
            'user_id' => $user->id,
            'employee_id' => $employeeId,
            'gender' => 'male',
        ]);

        $emailValidator = Validator::make(
            ['email' => $email],
            ['email' => ['required', 'email', unique_active_user_email_rule()]]
        );
        $this->assertTrue($emailValidator->fails());

        $employeeIdValidator = Validator::make(
            ['employee_id' => $employeeId],
            ['employee_id' => ['required', unique_active_employee_id_rule()]]
        );
        $this->assertTrue($employeeIdValidator->fails());

        $emailIgnoreSelf = Validator::make(
            ['email' => $email],
            ['email' => ['required', 'email', unique_active_user_email_rule($user->id)]]
        );
        $this->assertFalse($emailIgnoreSelf->fails());

        $employeeIdIgnoreSelf = Validator::make(
            ['employee_id' => $employeeId],
            ['employee_id' => ['required', unique_active_employee_id_rule($user->id)]]
        );
        $this->assertFalse($employeeIdIgnoreSelf->fails());
    }
}
