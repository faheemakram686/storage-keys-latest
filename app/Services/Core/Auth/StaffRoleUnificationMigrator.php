<?php

namespace App\Services\Core\Auth;

use App\Models\Core\Auth\Permission;
use App\Models\Core\Auth\Role;
use App\Models\Core\Auth\Type;
use App\Models\Core\Auth\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

/**
 * Idempotent migration: one staff role per user, admin roles retargeted to tenant type.
 */
class StaffRoleUnificationMigrator
{
    public function run(): void
    {
        if (!Schema::hasTable('roles') || !Schema::hasTable('role_user') || !Schema::hasTable('types')) {
            return;
        }

        $tenantType = Type::findByAlias(UserRoleSyncService::TYPE_TENANT);
        $adminType = Type::findByAlias(UserRoleSyncService::TYPE_ADMIN);

        if (!$tenantType) {
            return;
        }

        DB::transaction(function () use ($tenantType, $adminType) {
            if ($adminType) {
                Role::query()
                    ->where('type_id', $adminType->id)
                    ->where(function ($q) {
                        $q->where('is_admin', 0)->orWhereNull('is_admin');
                    })
                    ->update(['type_id' => $tenantType->id]);
            }

            $manager = Role::query()->where('alias', 'manager')->first();
            if ($manager && $adminType) {
                $crmPermissionIds = Permission::query()
                    ->where('type_id', $adminType->id)
                    ->pluck('id')
                    ->all();

                if (!empty($crmPermissionIds)) {
                    $manager->permissions()->detach($crmPermissionIds);
                }
            }

            $sync = resolve(UserRoleSyncService::class);
            $employeeRole = Role::query()->where('alias', 'employee')->first();

            User::query()
                ->with(['roles.type', 'roles.permissions'])
                ->chunkById(100, function ($users) use ($sync, $employeeRole, $tenantType) {
                    foreach ($users as $user) {
                        $this->unifyUser($user, $sync, $employeeRole, $tenantType);
                    }
                });
        });
    }

    protected function unifyUser(User $user, UserRoleSyncService $sync, ?Role $employeeRole, Type $tenantType): void
    {
        $staffRoles = $user->roles->filter(fn (Role $role) => $sync->isStaffRole($role))->values();

        if ($staffRoles->isEmpty()) {
            if ((int) ($user->is_in_employee ?? 0) === 1 && $employeeRole) {
                $user->roles()->syncWithoutDetaching([$employeeRole->id]);
                $this->setUserType($user, $employeeRole->id);
                $sync->clearUserRoleCache($user);
                Log::info('role_unification: assigned default employee', [
                    'user_id' => $user->id,
                    'new_role_id' => $employeeRole->id,
                ]);
            }

            return;
        }

        if ($staffRoles->count() === 1) {
            $only = $staffRoles->first();
            $this->setUserType($user, $only->id);

            return;
        }

        $oldIds = $staffRoles->pluck('id')->sort()->values()->all();
        $combinedName = $staffRoles->pluck('name')->implode(' + ');
        $combinedAlias = 'combined_' . md5(implode('_', $oldIds));

        $combined = Role::query()->firstOrCreate(
            ['alias' => $combinedAlias],
            [
                'name' => $combinedName,
                'type_id' => $tenantType->id,
                'is_admin' => 0,
                'is_default' => 0,
                'created_by' => 1,
            ]
        );

        if ($combined->name !== $combinedName || (int) $combined->type_id !== (int) $tenantType->id) {
            $combined->name = $combinedName;
            $combined->type_id = $tenantType->id;
            $combined->save();
        }

        $permissionIds = $staffRoles
            ->flatMap(fn (Role $role) => $role->permissions->pluck('id'))
            ->unique()
            ->values()
            ->all();

        $combined->permissions()->sync($permissionIds);

        $sync->detachStaffRoles($user);
        $user->roles()->attach($combined->id);
        $this->setUserType($user, $combined->id);
        $sync->clearUserRoleCache($user);

        Log::info('role_unification: collapsed dual roles', [
            'user_id' => $user->id,
            'old_role_ids' => $oldIds,
            'new_role_id' => $combined->id,
            'combined_name' => $combinedName,
        ]);
    }

    protected function setUserType(User $user, int $roleId): void
    {
        if (!Schema::hasColumn('users', 'user_type')) {
            return;
        }

        if ((int) $user->user_type !== $roleId) {
            $user->user_type = $roleId;
            $user->save();
        }
    }
}
