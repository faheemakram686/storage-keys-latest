<?php

namespace App\Models\Core\Auth\Traits\Method;

/**
 * Trait RoleMethod.
 */
trait RoleMethod
{
    /**
     * @return mixed
     */
    public function isDefault()
    {
        return $this->is_default;
    }

    public function isAdmin()
    {
        return $this->is_admin;
    }

    public static function findByName(string $role_name)
    {
        return self::query()->whereName($role_name)->first();
    }

    public function hasPermission(string $permission_name): bool
    {
        return $this->permissions()
            ->where('name', $permission_name)->exists();
    }

    /**
     * @param  array<int, string>  $permission_names
     */
    public function hasAnyPermission(array $permission_names): bool
    {
        if (empty($permission_names)) {
            return false;
        }

        return $this->permissions()
            ->whereIn('name', $permission_names)
            ->exists();
    }

    /**
     * @param  array<int, string>  $permission_names
     */
    public function hasAllPermissions(array $permission_names): bool
    {
        if (empty($permission_names)) {
            return true;
        }

        return $this->permissions()
            ->whereIn('name', $permission_names)
            ->count() === count(array_unique($permission_names));
    }
}
