<?php

namespace Tests\Feature;

use App\Models\Contact;
use App\Models\ContactPermission;
use App\Models\ContactRole;
use App\Models\Core\Auth\Permission;
use App\Models\Core\Auth\Role;
use App\Models\Core\Auth\Type;
use App\Models\Core\Auth\User;
use App\Models\Customer;
use App\Services\Contact\ContactRoleSyncService;
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
    public function syncing_portal_role_does_not_wipe_hrm_role()
    {
        $user = $this->makeUser();
        $portal = $this->makeAdminRole('Portal Test ' . uniqid());
        $hrm = $this->makeTenantRole('HRM Test ' . uniqid());

        $this->sync->syncHrmRole($user, $hrm->id);
        $this->sync->syncPortalRole($user, $portal->id);

        $user->refresh()->load('roles.type');

        $this->assertTrue($user->roles->contains('id', $portal->id));
        $this->assertTrue($user->roles->contains('id', $hrm->id));
        $this->assertCount(2, $user->roles);
    }

    /** @test */
    public function syncing_hrm_role_preserves_existing_portal_role()
    {
        $user = $this->makeUser();
        $portal = $this->makeAdminRole('Portal Keep ' . uniqid());
        $hrmA = $this->makeTenantRole('HRM A ' . uniqid());
        $hrmB = $this->makeTenantRole('HRM B ' . uniqid());

        $this->sync->syncPortalRole($user, $portal->id);
        $this->sync->syncHrmRole($user, $hrmA->id);
        $this->sync->syncHrmRole($user, $hrmB->id);

        $user->refresh()->load('roles');

        $this->assertTrue($user->roles->contains('id', $portal->id));
        $this->assertTrue($user->roles->contains('id', $hrmB->id));
        $this->assertFalse($user->roles->contains('id', $hrmA->id));
    }

    /** @test */
    public function permission_helper_unions_permissions_across_roles()
    {
        $user = $this->makeUser();
        $portal = $this->makeAdminRole('Portal Perm ' . uniqid());
        $hrm = $this->makeTenantRole('HRM Perm ' . uniqid());

        $viewCustomer = $this->ensurePermission('view_customer', 'admin');
        $viewEmployees = $this->ensurePermission('view_employees', 'tenant');

        $portal->permissions()->sync([$viewCustomer->id]);
        $hrm->permissions()->sync([$viewEmployees->id]);

        $this->sync->syncPortalRole($user, $portal->id);
        $this->sync->syncHrmRole($user, $hrm->id);

        $this->assertTrue($this->sync->userHasPermission($user, 'view_customer'));
        $this->assertTrue($this->sync->userHasPermission($user, 'view_employees'));
        $this->assertTrue($this->sync->userHasAnyPermission($user, ['view_customer', 'missing']));
        $this->assertFalse($this->sync->userHasPermission($user, 'delete_nuclear'));
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

    /** @test */
    public function app_admin_bypasses_portal_permission_checks()
    {
        $adminType = Type::findByAlias('app') ?: Type::create(['name' => 'App', 'alias' => 'app']);
        $user = $this->makeUser();

        $role = Role::create([
            'name' => 'App Admin Test ' . uniqid(),
            'alias' => 'admin_test_' . uniqid(),
            'type_id' => $adminType->id,
            'is_admin' => 1,
            'is_default' => 1,
        ]);
        $user->roles()->attach($role->id);

        // Clear cached isAppAdmin
        cache()->forget('app-admin-' . $user->id);

        $this->assertTrue($this->sync->userHasPermission($user->fresh(), 'any_random_permission'));
        $this->assertTrue($this->sync->hasPortalAccess($user->fresh()));
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

    protected function makeUser(): User
    {
        return User::create([
            'first_name' => 'Sync',
            'last_name' => 'Test',
            'email' => 'sync' . uniqid() . '@example.com',
            'password' => Hash::make('password'),
            'status_id' => 1,
        ]);
    }

    protected function makeAdminRole(string $name): Role
    {
        return Role::create([
            'name' => $name,
            'alias' => Str::slug($name) . '_' . uniqid(),
            'type_id' => Type::findByAlias('admin')->id,
            'is_admin' => 0,
            'is_default' => 0,
        ]);
    }

    protected function makeTenantRole(string $name): Role
    {
        return Role::create([
            'name' => $name,
            'alias' => Str::slug($name) . '_' . uniqid(),
            'type_id' => Type::findByAlias('tenant')->id,
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
