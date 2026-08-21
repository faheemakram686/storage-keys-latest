<?php

namespace Tests\Feature;

use App\Models\Contact;
use App\Models\ContactRole;
use App\Models\Core\Auth\Permission;
use App\Models\Core\Auth\Role;
use App\Models\Core\Auth\Type;
use App\Models\Core\Auth\User;
use App\Models\Customer;
use App\Rules\RoleExistRule;
use App\Services\Contact\ContactRoleSyncService;
use App\Services\Core\Auth\StaffRoleUnificationMigrator;
use App\Services\Core\Auth\UserRoleSyncService;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Tests\CreatesApplication;

class RoleUnificationTest extends BaseTestCase
{
    use CreatesApplication;
    use DatabaseTransactions;

    private UserRoleSyncService $sync;

    protected function setUp(): void
    {
        parent::setUp();
        $this->sync = resolve(UserRoleSyncService::class);
        $this->ensureTypes();
    }

    /** @test */
    public function syncing_staff_role_replaces_all_previous_staff_roles()
    {
        $user = $this->makeUser();
        $first = $this->makeStaffRole('Staff First ' . uniqid());
        $second = $this->makeStaffRole('Staff Second ' . uniqid());

        $this->sync->syncStaffRole($user, $first->id);
        $this->sync->syncStaffRole($user, $second->id);

        $user->refresh()->load('roles');

        $this->assertTrue($user->roles->contains('id', $second->id));
        $this->assertFalse($user->roles->contains('id', $first->id));
        $this->assertCount(1, $user->roles->filter(fn ($r) => $this->sync->isStaffRole($r)));
    }

    /** @test */
    public function legacy_sync_portal_and_hrm_wrappers_use_single_staff_role()
    {
        $user = $this->makeUser();
        $a = $this->makeStaffRole('Wrapper A ' . uniqid());
        $b = $this->makeStaffRole('Wrapper B ' . uniqid());

        $this->sync->syncPortalRole($user, $a->id);
        $this->sync->syncHrmRole($user, $b->id);

        $user->refresh()->load('roles');

        $this->assertTrue($user->roles->contains('id', $b->id));
        $this->assertFalse($user->roles->contains('id', $a->id));
        $this->assertCount(1, $user->roles);
    }

    /** @test */
    public function permission_helper_reads_permissions_from_single_staff_role()
    {
        $user = $this->makeUser();
        $role = $this->makeStaffRole('Mixed Perm ' . uniqid());

        $viewCustomer = $this->ensurePermission('view_customer', 'admin');
        $viewEmployees = $this->ensurePermission('view_employees', 'tenant');

        $role->permissions()->sync([$viewCustomer->id, $viewEmployees->id]);
        $this->sync->syncStaffRole($user, $role->id);

        $this->assertTrue($this->sync->userHasPermission($user, 'view_customer'));
        $this->assertTrue($this->sync->userHasPermission($user, 'view_employees'));
        $this->assertTrue($this->sync->userHasAnyPermission($user, ['view_customer', 'missing']));
        $this->assertFalse($this->sync->userHasPermission($user, 'delete_nuclear'));
    }

    /** @test */
    public function portal_access_requires_crm_typed_permission()
    {
        $user = $this->makeUser();
        $hrmOnly = $this->makeStaffRole('HRM Only ' . uniqid());
        $viewEmployees = $this->ensurePermission('view_employees', 'tenant');
        $hrmOnly->permissions()->sync([$viewEmployees->id]);
        $this->sync->syncStaffRole($user, $hrmOnly->id);

        $this->assertFalse($this->sync->hasPortalAccess($user->fresh()));
        $this->assertTrue($this->sync->hasHrmAccess($user->fresh()));

        $crm = $this->makeStaffRole('CRM Access ' . uniqid());
        $viewCustomer = $this->ensurePermission('view_customer', 'admin');
        $crm->permissions()->sync([$viewCustomer->id]);
        $this->sync->syncStaffRole($user, $crm->id);

        $this->assertTrue($this->sync->hasPortalAccess($user->fresh()));
        $this->assertFalse($this->sync->hasHrmAccess($user->fresh()));
    }

    /** @test */
    public function mixed_role_grants_both_portal_and_hrm_access()
    {
        $user = $this->makeUser();
        $mixed = $this->makeStaffRole('Mixed Access ' . uniqid());
        $viewCustomer = $this->ensurePermission('view_customer', 'admin');
        $viewEmployees = $this->ensurePermission('view_employees', 'tenant');
        $mixed->permissions()->sync([$viewCustomer->id, $viewEmployees->id]);
        $this->sync->syncStaffRole($user, $mixed->id);

        $this->assertTrue($this->sync->hasPortalAccess($user->fresh()));
        $this->assertTrue($this->sync->hasHrmAccess($user->fresh()));
    }

    /** @test */
    public function cannot_assign_app_admin_via_sync_staff_role()
    {
        $user = $this->makeUser();
        $appType = Type::findByAlias('app');
        $appAdmin = Role::create([
            'name' => 'App Admin Sync ' . uniqid(),
            'alias' => 'admin_sync_' . uniqid(),
            'type_id' => $appType->id,
            'is_admin' => 1,
            'is_default' => 0,
        ]);

        $this->expectException(\App\Exceptions\GeneralException::class);
        $this->sync->syncStaffRole($user, $appAdmin->id);
    }

    /** @test */
    public function staff_role_exist_rule_accepts_staff_and_rejects_app_admin()
    {
        $staff = $this->makeStaffRole('Employee Import ' . uniqid());
        $appType = Type::findByAlias('app');
        $appAdmin = Role::create([
            'name' => 'App Admin Import ' . uniqid(),
            'alias' => 'admin_import_' . uniqid(),
            'type_id' => $appType->id,
            'is_admin' => 1,
            'is_default' => 0,
        ]);

        $rule = new RoleExistRule();

        $this->assertTrue($rule->passes('role', $staff->name));
        $this->assertFalse($rule->passes('role', $appAdmin->name));
        $this->assertFalse($rule->passes('role', $appAdmin->name . ',' . $staff->name));
        $this->assertFalse($rule->passes('role', $staff->name . ',Extra'));
    }

    /** @test */
    public function syncing_staff_roles_by_name_ignores_app_admin_same_name()
    {
        $user = $this->makeUser();
        $sharedName = 'Manager Clash ' . uniqid();
        $appType = Type::findByAlias('app');
        Role::create([
            'name' => $sharedName,
            'alias' => 'app_clash_' . uniqid(),
            'type_id' => $appType->id,
            'is_admin' => 1,
            'is_default' => 0,
        ]);
        $staff = $this->makeStaffRole($sharedName);

        $this->sync->syncStaffRoles($user, [$sharedName]);

        $user->refresh()->load('roles');

        $this->assertTrue($user->roles->contains('id', $staff->id));
        $this->assertCount(1, $user->roles);
    }

    /** @test */
    public function app_admin_bypasses_portal_permission_checks()
    {
        $adminType = Type::findByAlias('app');
        $user = $this->makeUser();

        $role = Role::create([
            'name' => 'App Admin Test ' . uniqid(),
            'alias' => 'admin_test_' . uniqid(),
            'type_id' => $adminType->id,
            'is_admin' => 1,
            'is_default' => 1,
        ]);
        $user->roles()->attach($role->id);

        cache()->forget('app-admin-' . $user->id);

        $this->assertTrue($this->sync->userHasPermission($user->fresh(), 'any_random_permission'));
        $this->assertTrue($this->sync->hasPortalAccess($user->fresh()));
        $this->assertTrue($this->sync->hasHrmAccess($user->fresh()));
    }

    /** @test */
    public function migrator_collapses_dual_roles_into_combined_role()
    {
        $user = $this->makeUser(['is_in_employee' => 1]);
        $portal = $this->makeAdminTypedRole('Portal Staff Mig ' . uniqid());
        $hrm = $this->makeStaffRole('Employee Mig ' . uniqid());

        $viewCustomer = $this->ensurePermission('view_customer', 'admin');
        $viewEmployees = $this->ensurePermission('view_employees', 'tenant');
        $portal->permissions()->sync([$viewCustomer->id]);
        $hrm->permissions()->sync([$viewEmployees->id]);

        $user->roles()->attach([$portal->id, $hrm->id]);

        resolve(StaffRoleUnificationMigrator::class)->run();

        $user->refresh()->load(['roles.permissions', 'roles.type']);

        $this->assertCount(1, $user->roles);
        $combined = $user->roles->first();
        $this->assertStringContainsString('+', $combined->name);
        $this->assertTrue($combined->permissions->contains('id', $viewCustomer->id));
        $this->assertTrue($combined->permissions->contains('id', $viewEmployees->id));
        $this->assertEquals('tenant', optional($combined->type)->alias);
        $this->assertTrue($this->sync->hasPortalAccess($user));
        $this->assertTrue($this->sync->hasHrmAccess($user));

        // Idempotent second run
        resolve(StaffRoleUnificationMigrator::class)->run();
        $user->refresh()->load('roles');
        $this->assertCount(1, $user->roles);
        $this->assertEquals($combined->id, $user->roles->first()->id);
    }

    /** @test */
    public function migrator_strips_crm_permissions_from_manager_and_retypes_admin_roles()
    {
        $adminType = Type::findByAlias('admin');
        $tenantType = Type::findByAlias('tenant');

        $manager = Role::query()->firstOrCreate(
            ['alias' => 'manager'],
            [
                'name' => 'Manager',
                'type_id' => $tenantType->id,
                'is_admin' => 0,
                'is_default' => 1,
            ]
        );

        $crmPerm = $this->ensurePermission('view_customer', 'admin');
        $hrmPerm = $this->ensurePermission('view_employees', 'tenant');
        $manager->permissions()->syncWithoutDetaching([$crmPerm->id, $hrmPerm->id]);

        $portalRole = $this->makeAdminTypedRole('Portal Retype ' . uniqid());

        resolve(StaffRoleUnificationMigrator::class)->run();

        $manager->refresh()->load('permissions');
        $portalRole->refresh()->load('type');

        $this->assertFalse($manager->permissions->contains('id', $crmPerm->id));
        $this->assertTrue($manager->permissions->contains('id', $hrmPerm->id));
        $this->assertEquals($tenantType->id, $portalRole->type_id);
        $this->assertNotEquals($adminType->id, $portalRole->type_id);
    }

    /** @test */
    public function contact_owner_can_update_account_viewer_cannot_pay()
    {
        if (!Schema::hasTable('contact_roles')) {
            $this->markTestSkipped('contact_roles table missing — run migrations');
        }

        $this->seedContactRoles();

        $customer = Customer::create([
            'customer_name' => 'Role Test Co ' . uniqid(),
            'customer_type' => 'company',
            'email' => 'co' . uniqid() . '@example.com',
            'status' => 1,
            'is_deleted' => 0,
        ]);

        $owner = Contact::create([
            'customer_id' => $customer->id,
            'first_name' => 'Owner',
            'last_name' => 'Test',
            'email' => 'owner' . uniqid() . '@example.com',
            'password' => Hash::make('password'),
            'status' => 1,
            'contact_type' => 'primary',
            'is_deleted' => 0,
        ]);

        $viewer = Contact::create([
            'customer_id' => $customer->id,
            'first_name' => 'Viewer',
            'last_name' => 'Test',
            'email' => 'viewer' . uniqid() . '@example.com',
            'password' => Hash::make('password'),
            'status' => 1,
            'contact_type' => 'general',
            'is_deleted' => 0,
        ]);

        $contactSync = resolve(ContactRoleSyncService::class);
        $contactSync->assignRole($owner, ContactRoleSyncService::OWNER);
        $contactSync->assignRole($viewer, ContactRoleSyncService::VIEWER);

        $owner->refresh();
        $viewer->refresh();

        $this->assertTrue($owner->hasContactPermission('update_account'));
        $this->assertTrue($owner->hasContactPermission('pay_invoices'));
        $this->assertEquals('primary', $owner->getAttributes()['contact_type'] ?? $owner->contact_type);

        $this->assertFalse($viewer->hasContactPermission('update_account'));
        $this->assertFalse($viewer->hasContactPermission('pay_invoices'));
        $this->assertTrue($viewer->hasContactPermission('view_invoices'));
        $this->assertEquals('general', $viewer->getAttributes()['contact_type'] ?? null);
    }

    protected function ensureTypes(): void
    {
        foreach (['app', 'tenant', 'admin'] as $alias) {
            Type::query()->firstOrCreate(
                ['alias' => $alias],
                ['name' => ucfirst($alias)]
            );
        }
    }

    protected function makeUser(array $extra = []): User
    {
        return User::create(array_merge([
            'first_name' => 'Sync',
            'last_name' => 'Test',
            'email' => 'sync' . uniqid() . '@example.com',
            'password' => Hash::make('password'),
            'status_id' => 1,
        ], $extra));
    }

    protected function makeStaffRole(string $name): Role
    {
        return Role::create([
            'name' => $name,
            'alias' => Str::slug($name) . '_' . uniqid(),
            'type_id' => Type::findByAlias('tenant')->id,
            'is_admin' => 0,
            'is_default' => 0,
        ]);
    }

    /** Admin-typed role as it existed before migration. */
    protected function makeAdminTypedRole(string $name): Role
    {
        return Role::create([
            'name' => $name,
            'alias' => Str::slug($name) . '_' . uniqid(),
            'type_id' => Type::findByAlias('admin')->id,
            'is_admin' => 0,
            'is_default' => 0,
        ]);
    }

    protected function ensurePermission(string $name, string $typeAlias): Permission
    {
        $type = Type::findByAlias($typeAlias);

        return Permission::query()->firstOrCreate(
            ['name' => $name],
            [
                'type_id' => $type->id,
                'group_name' => 'test',
            ]
        );
    }

    protected function seedContactRoles(): void
    {
        if (ContactRole::query()->count() === 0) {
            (new \Database\Seeders\Contact\ContactRoleSeeder())->run();
        }
    }
}
