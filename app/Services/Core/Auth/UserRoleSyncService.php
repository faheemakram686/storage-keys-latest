<?php

namespace App\Services\Core\Auth;

use App\Exceptions\GeneralException;
use App\Models\Core\Auth\Role;
use App\Models\Core\Auth\Type;
use App\Models\Core\Auth\User;
use App\Models\User as AppUser;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class UserRoleSyncService
{
    public const TYPE_APP = 'app';
    public const TYPE_ADMIN = 'admin';
    public const TYPE_TENANT = 'tenant';

    /**
     * Replace the user's portal (admin-type) role without touching HRM roles.
     *
     * @param  User|AppUser  $user
     */
    public function syncPortalRole($user, $roleId): User
    {
        return $this->syncRoleByType($this->toAuthUser($user), $roleId, self::TYPE_ADMIN);
    }

    /**
     * Replace the user's HRM (tenant-type) role without touching portal roles.
     *
     * @param  User|AppUser  $user
     */
    public function syncHrmRole($user, $roleId): User
    {
        return $this->syncRoleByType($this->toAuthUser($user), $roleId, self::TYPE_TENANT);
    }

    /**
     * Sync one or more HRM (tenant-type) roles, preserving portal/app roles.
     *
     * @param  User|AppUser  $user
     * @param  array<int|string|Role>  $roles
     */
    public function syncHrmRoles($user, array $roles): User
    {
        $user = $this->toAuthUser($user);
        $roleIds = $this->normalizeRoleIds($roles);

        if (empty($roleIds)) {
            $this->detachRolesOfType($user, self::TYPE_TENANT);
            $this->clearUserRoleCache($user);

            return $user->fresh(['roles']);
        }

        $tenantRoles = Role::query()
            ->whereIn('id', $roleIds)
            ->whereHas('type', fn ($q) => $q->where('alias', self::TYPE_TENANT))
            ->pluck('id')
            ->all();

        if (count($tenantRoles) !== count(array_unique($roleIds))) {
            throw new GeneralException(__('Only HRM (tenant) roles can be assigned from HRM.'));
        }

        $this->detachRolesOfType($user, self::TYPE_TENANT);
        $user->roles()->attach($tenantRoles);
        $this->clearUserRoleCache($user);

        return $user->fresh(['roles']);
    }

    /**
     * @param  User|AppUser  $user
     * @param  int|string|Role|null  $roleId
     */
    public function syncRoleByType($user, $roleId, string $typeAlias): User
    {
        $user = $this->toAuthUser($user);

        if ($roleId === null || $roleId === '' || $roleId === 0) {
            $this->detachRolesOfType($user, $typeAlias);
            $this->clearUserRoleCache($user);

            return $user->fresh(['roles']);
        }

        $role = $roleId instanceof Role
            ? $roleId
            : Role::with('type')->findOrFail((int) $roleId);

        $role->loadMissing('type');

        if (optional($role->type)->alias !== $typeAlias) {
            throw new GeneralException(sprintf(
                'Role "%s" is not a %s role.',
                $role->name,
                $typeAlias
            ));
        }

        if ($role->isAdmin() && optional($role->type)->alias === self::TYPE_APP) {
            throw new GeneralException(__('App Admin cannot be assigned from this form.'));
        }

        $this->detachRolesOfType($user, $typeAlias);
        $user->roles()->attach($role->id);
        $this->clearUserRoleCache($user);

        return $user->fresh(['roles']);
    }

    /**
     * @param  User|AppUser  $user
     */
    public function detachRolesOfType($user, string $typeAlias): void
    {
        $user = $this->toAuthUser($user);

        $ids = $user->roles()
            ->whereHas('type', fn ($q) => $q->where('alias', $typeAlias))
            ->pluck('roles.id')
            ->all();

        if (!empty($ids)) {
            $user->roles()->detach($ids);
        }
    }

    /**
     * @param  User|AppUser  $user
     */
    public function getPortalRole($user): ?Role
    {
        return $this->getRoleOfType($this->toAuthUser($user), self::TYPE_ADMIN);
    }

    /**
     * @param  User|AppUser  $user
     */
    public function getHrmRole($user): ?Role
    {
        return $this->getRoleOfType($this->toAuthUser($user), self::TYPE_TENANT);
    }

    /**
     * @param  User|AppUser  $user
     */
    public function getRoleOfType($user, string $typeAlias): ?Role
    {
        $user = $this->toAuthUser($user);
        $user->loadMissing('roles.type');

        return $user->roles->first(
            fn (Role $role) => optional($role->type)->alias === $typeAlias
        );
    }

    /**
     * Union of permission names across all roles. App Admin bypasses all checks.
     *
     * @param  User|AppUser  $user
     */
    public function userHasPermission($user, string $permission): bool
    {
        $user = $this->toAuthUser($user);

        if ($user->isAppAdmin()) {
            return true;
        }

        return $this->permissionNames($user)->contains($permission);
    }

    /**
     * @param  User|AppUser  $user
     * @param  array<int, string>  $permissions
     */
    public function userHasAnyPermission($user, array $permissions): bool
    {
        $user = $this->toAuthUser($user);

        if ($user->isAppAdmin()) {
            return true;
        }

        if (empty($permissions)) {
            return false;
        }

        return $this->permissionNames($user)->intersect($permissions)->isNotEmpty();
    }

    /**
     * @param  User|AppUser  $user
     * @param  array<int, string>  $permissions
     */
    public function userHasAllPermissions($user, array $permissions): bool
    {
        $user = $this->toAuthUser($user);

        if ($user->isAppAdmin()) {
            return true;
        }

        if (empty($permissions)) {
            return true;
        }

        $owned = $this->permissionNames($user);

        foreach ($permissions as $permission) {
            if (!$owned->contains($permission)) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param  User|AppUser  $user
     */
    public function permissionNames($user): Collection
    {
        $user = $this->toAuthUser($user);
        $user->loadMissing('roles.permissions');

        return $user->roles
            ->flatMap(fn (Role $role) => $role->permissions->pluck('name'))
            ->unique()
            ->values();
    }

    /**
     * @param  User|AppUser  $user
     */
    public function hasPortalAccess($user): bool
    {
        $user = $this->toAuthUser($user);

        if ($user->isAppAdmin()) {
            return true;
        }

        return $user->roles()
            ->whereHas('type', fn ($q) => $q->where('alias', self::TYPE_ADMIN))
            ->exists();
    }

    /**
     * @param  User|AppUser  $user
     */
    public function hasHrmAccess($user): bool
    {
        $user = $this->toAuthUser($user);

        if ($user->isAppAdmin()) {
            return true;
        }

        return $user->roles()
            ->whereHas('type', fn ($q) => $q->whereIn('alias', [self::TYPE_TENANT, self::TYPE_APP]))
            ->exists();
    }

    public function rolesForType(string $typeAlias): Collection
    {
        $type = Type::findByAlias($typeAlias);

        if (!$type) {
            return collect();
        }

        return Role::query()
            ->where('type_id', $type->id)
            ->when($typeAlias === self::TYPE_ADMIN, function ($q) {
                $q->where(function ($inner) {
                    $inner->where('is_admin', 0)->orWhereNull('is_admin');
                });
            })
            ->orderBy('name')
            ->get(['id', 'name', 'alias', 'type_id', 'is_admin', 'is_default']);
    }

    /**
     * @param  User|AppUser  $user
     */
    public function clearUserRoleCache($user): void
    {
        $user = $this->toAuthUser($user);

        Cache::forget('user-' . $user->id);
        Cache::forget('user-roles-permissions-' . $user->id);
        Cache::forget('user-roles-' . $user->id);
        Cache::forget('auth-user-permissions-' . $user->id);
        Cache::forget('app-admin-' . $user->id);
        Cache::forget('brand-admin-' . $user->id);
    }

    /**
     * Portal user CRUD uses App\Models\User; HRM/auth uses App\Models\Core\Auth\User.
     * Both map to the same users table, so normalize before typed Eloquent work.
     *
     * @param  User|AppUser|object  $user
     */
    protected function toAuthUser($user): User
    {
        if ($user instanceof User) {
            return $user;
        }

        if (is_object($user) && isset($user->id)) {
            return User::query()->findOrFail($user->id);
        }

        throw new \InvalidArgumentException('A user instance is required for role sync.');
    }

    /**
     * @param  array<int|string|Role>  $roles
     * @return array<int>
     */
    protected function normalizeRoleIds(array $roles): array
    {
        return collect($roles)
            ->map(function ($role) {
                if ($role instanceof Role) {
                    return (int) $role->id;
                }

                if (is_numeric($role)) {
                    return (int) $role;
                }

                if (is_string($role)) {
                    $found = Role::findByName($role);

                    return $found ? (int) $found->id : null;
                }

                return null;
            })
            ->filter()
            ->unique()
            ->values()
            ->all();
    }
}
