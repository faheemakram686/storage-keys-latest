<?php

use App\Models\Core\Auth\User;
use App\Services\Core\Auth\UserRoleSyncService;

if (!function_exists('user_has_permission')) {
    /**
     * Check if the given (or authenticated) user has a permission via union of all roles.
     * App Admin always returns true.
     */
    function user_has_permission(string $permission, ?User $user = null): bool
    {
        $user = $user ?: auth()->user();

        if (!$user instanceof User) {
            return false;
        }

        return resolve(UserRoleSyncService::class)->userHasPermission($user, $permission);
    }
}

if (!function_exists('user_has_any_permission')) {
    /**
     * @param  array<int, string>  $permissions
     */
    function user_has_any_permission(array $permissions, ?User $user = null): bool
    {
        $user = $user ?: auth()->user();

        if (!$user instanceof User) {
            return false;
        }

        return resolve(UserRoleSyncService::class)->userHasAnyPermission($user, $permissions);
    }
}

if (!function_exists('user_permission_names')) {
    /**
     * @return \Illuminate\Support\Collection<int, string>
     */
    function user_permission_names(?User $user = null)
    {
        $user = $user ?: auth()->user();

        if (!$user instanceof User) {
            return collect();
        }

        if ($user->isAppAdmin()) {
            return collect(['*']);
        }

        return resolve(UserRoleSyncService::class)->permissionNames($user);
    }
}

if (!function_exists('user_has_portal_access')) {
    function user_has_portal_access(?User $user = null): bool
    {
        $user = $user ?: auth()->user();

        if (!$user instanceof User) {
            return false;
        }

        return resolve(UserRoleSyncService::class)->hasPortalAccess($user);
    }
}

if (!function_exists('user_has_hrm_access')) {
    function user_has_hrm_access(?User $user = null): bool
    {
        $user = $user ?: auth()->user();

        if (!$user instanceof User) {
            return false;
        }

        return resolve(UserRoleSyncService::class)->hasHrmAccess($user);
    }
}
