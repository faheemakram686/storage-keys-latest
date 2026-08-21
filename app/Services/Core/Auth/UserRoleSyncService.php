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
     * Staff roles are everything except App Admin (app type / is_admin).
     */
    public function isStaffRole(Role $role): bool
    {
        $role->loadMissing('type');

        if ($role->isAdmin()) {
            return false;
        }

        return optional($role->type)->alias !== self::TYPE_APP;
    }

    /**
     * Replace all non-app roles with exactly one staff role.
     *
     * @param  User|AppUser  $user
     * @param  int|string|Role|null  $roleId
     */
    public function syncStaffRole($user, $roleId): User
    {
        $user = $this->toAuthUser($user);

        if ($roleId === null || $roleId === '' || $roleId === 0) {
            $this->detachStaffRoles($user);
            $this->clearUserRoleCache($user);

            return $user->fresh(['roles']);
        }

        $role = $roleId instanceof Role
            ? $roleId
            : Role::with('type')->findOrFail((int) $roleId);

        $role->loadMissing('type');

        if (!$this->isStaffRole($role)) {
            throw new GeneralException(__('App Admin cannot be assigned from this form.'));
        }

        $this->detachStaffRoles($user);
        $user->roles()->attach($role->id);
        $this->clearUserRoleCache($user);

        return $user->fresh(['roles']);
    }

    /**
     * Sync one or more staff roles (normalized to a single primary role when one id is given).
     * When multiple ids are provided, the first staff role is kept (single-role policy).
     *
     * @param  User|AppUser  $user
     * @param  array<int|string|Role>  $roles
     */
    public function syncStaffRoles($user, array $roles): User
    {
        $user = $this->toAuthUser($user);
        $roleIds = $this->normalizeStaffRoleIds($roles);

        if (empty($roleIds)) {
            $this->detachStaffRoles($user);
            $this->clearUserRoleCache($user);

            return $user->fresh(['roles']);
        }

        // Single-role policy: use the first resolved staff role.
        return $this->syncStaffRole($user, $roleIds[0]);
    }

    /**
     * @deprecated Prefer syncStaffRole — kept as a thin wrapper for legacy callers.
     *
     * @param  User|AppUser  $user
     */
    public function syncPortalRole($user, $roleId): User
    {
        return $this->syncStaffRole($user, $roleId);
    }

    /**
     * @deprecated Prefer syncStaffRole — kept as a thin wrapper for legacy callers.
     *
     * @param  User|AppUser  $user
     */
    public function syncHrmRole($user, $roleId): User
    {
        return $this->syncStaffRole($user, $roleId);
    }

    /**
     * @deprecated Prefer syncStaffRoles — kept as a thin wrapper for legacy callers.
     *
     * @param  User|AppUser  $user
     * @param  array<int|string|Role>  $roles
     */
    public function syncHrmRoles($user, array $roles): User
    {
        return $this->syncStaffRoles($user, $roles);
    }

    /**
     * @param  User|AppUser  $user
     * @param  int|string|Role|null  $roleId
     */
    public function syncRoleByType($user, $roleId, string $typeAlias): User
    {
        // Unified model: type alias no longer gates assignment; staff check does.
        return $this->syncStaffRole($user, $roleId);
    }

    /**
     * @param  User|AppUser  $user
     */
    public function detachStaffRoles($user): void
    {
        $user = $this->toAuthUser($user);

        $appTypeId = optional(Type::findByAlias(self::TYPE_APP))->id;

        $ids = $user->roles()
            ->when($appTypeId, fn ($q) => $q->where('roles.type_id', '!=', $appTypeId))
            ->where(function ($q) {
                $q->where('roles.is_admin', 0)->orWhereNull('roles.is_admin');
            })
            ->pluck('roles.id')
            ->all();

        if (!empty($ids)) {
            $user->roles()->detach($ids);
        }
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
     * Primary staff role for the user (first non-app role).
     *
     * @param  User|AppUser  $user
     */
    public function getStaffRole($user): ?Role
    {
        $user = $this->toAuthUser($user);
        $user->loadMissing('roles.type');

        return $user->roles->first(fn (Role $role) => $this->isStaffRole($role));
    }

    /**
     * @param  User|AppUser  $user
     */
    public function getPortalRole($user): ?Role
    {
        return $this->getStaffRole($user);
    }

    /**
     * @param  User|AppUser  $user
     */
    public function getHrmRole($user): ?Role
    {
        return $this->getStaffRole($user);
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
     * Portal/CRM access: App Admin or any attached permission typed as admin.
     *
     * @param  User|AppUser  $user
     */
    public function hasPortalAccess($user): bool
    {
        $user = $this->toAuthUser($user);

        if ($user->isAppAdmin()) {
            return true;
        }

        return $user->roles()
            ->whereHas('permissions.type', fn ($q) => $q->where('alias', self::TYPE_ADMIN))
            ->exists();
    }

    /**
     * HRM access: App Admin or any attached permission typed as tenant.
     *
     * @param  User|AppUser  $user
     */
    public function hasHrmAccess($user): bool
    {
        $user = $this->toAuthUser($user);

        if ($user->isAppAdmin()) {
            return true;
        }

        return $user->roles()
            ->whereHas('permissions.type', fn ($q) => $q->where('alias', self::TYPE_TENANT))
            ->exists();
    }

    /**
     * All assignable staff roles (excludes App Admin).
     */
    public function staffRoles(): Collection
    {
        $appType = Type::findByAlias(self::TYPE_APP);

        return Role::query()
            ->with('type')
            ->when($appType, fn ($q) => $q->where('type_id', '!=', $appType->id))
            ->where(function ($q) {
                $q->where('is_admin', 0)->orWhereNull('is_admin');
            })
            ->orderBy('name')
            ->get(['id', 'name', 'alias', 'type_id', 'is_admin', 'is_default']);
    }

    public function rolesForType(string $typeAlias): Collection
    {
        // After unification, callers that asked for admin or tenant roles get the full staff catalog.
        if (in_array($typeAlias, [self::TYPE_ADMIN, self::TYPE_TENANT], true)) {
            return $this->staffRoles();
        }

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
    protected function normalizeStaffRoleIds(array $roles): array
    {
        return collect($roles)
            ->map(function ($role) {
                if ($role instanceof Role) {
                    return $this->isStaffRole($role) ? (int) $role->id : null;
                }

                if (is_numeric($role)) {
                    $found = Role::with('type')->find((int) $role);

                    return ($found && $this->isStaffRole($found)) ? (int) $found->id : null;
                }

                if (is_string($role)) {
                    $found = Role::query()
                        ->with('type')
                        ->where('name', trim($role))
                        ->get()
                        ->first(fn (Role $r) => $this->isStaffRole($r));

                    return $found ? (int) $found->id : null;
                }

                return null;
            })
            ->filter()
            ->unique()
            ->values()
            ->all();
    }

    /**
     * @param  array<int|string|Role>  $roles
     * @return array<int>
     */
    protected function normalizeRoleIds(array $roles, ?string $typeAlias = null): array
    {
        if ($typeAlias === null || in_array($typeAlias, [self::TYPE_ADMIN, self::TYPE_TENANT], true)) {
            return $this->normalizeStaffRoleIds($roles);
        }

        return collect($roles)
            ->map(function ($role) use ($typeAlias) {
                if ($role instanceof Role) {
                    return (int) $role->id;
                }

                if (is_numeric($role)) {
                    return (int) $role;
                }

                if (is_string($role)) {
                    $query = Role::query()->where('name', trim($role));

                    if ($typeAlias) {
                        $query->whereHas('type', fn ($q) => $q->where('alias', $typeAlias));
                    }

                    $found = $query->first();

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
