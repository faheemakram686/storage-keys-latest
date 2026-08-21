<?php

namespace App\Rules;

use App\Models\Core\Auth\Role;
use App\Services\Core\Auth\UserRoleSyncService;
use Illuminate\Contracts\Validation\Rule;

/**
 * Import validation: exactly one existing staff role name (not App Admin).
 */
class RoleExistRule implements Rule
{
    public function passes($attribute, $value)
    {
        if (!is_string($value) && !is_numeric($value)) {
            return false;
        }

        $name = trim((string) $value);

        // Reject comma-separated multi-role values — one role only.
        if ($name === '' || str_contains($name, ',')) {
            return false;
        }

        $sync = resolve(UserRoleSyncService::class);

        $role = Role::query()
            ->with('type')
            ->where('name', $name)
            ->get()
            ->first(fn (Role $role) => $sync->isStaffRole($role));

        return (bool) $role;
    }

    public function message()
    {
        return trans('default.is_invalid_message', ['subject' => __t('role')]);
    }
}
