<?php

use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Unique;

if (! function_exists('home_route')) {
    /**
     * Return the route to the "home" page depending on authentication/authorization status.
     *
     * @return array
     */
    function home_route()
    {
        if (auth()->guard('contact')->check()) {
            return [
                'route_name' => 'customer.dashboard',
                'route_params' => null
            ];
        }

        if (auth()->check()) {
            $user = auth()->user();
            $sync = resolve(\App\Services\Core\Auth\UserRoleSyncService::class);

            if ($user->isAppAdmin() || $sync->hasPortalAccess($user)) {
                return [
                    'route_name' => 'admin.index',
                    'route_params' => null
                ];
            }

            return [
                'route_name' => 'tenant.dashboard',
                'route_params' => null
            ];
        }
        return [
            'route_name' => 'users.login.index',
            'route_params' => null
        ];
    }
}

if (! function_exists('unique_active_user_email_rule')) {
    /**
     * Unique email among active (non soft-deleted) users only.
     *
     * @param  int|string|null  $ignoreId
     */
    function unique_active_user_email_rule($ignoreId = null): Unique
    {
        $rule = Rule::unique('users', 'email')->whereNull('deleted_at');

        if ($ignoreId !== null) {
            $rule->ignore($ignoreId);
        }

        return $rule;
    }
}

if (! function_exists('unique_active_employee_id_rule')) {
    /**
     * Unique employee_id among profiles whose user is not soft-deleted.
     *
     * @param  int|string|null  $ignoreUserId
     */
    function unique_active_employee_id_rule($ignoreUserId = null): Unique
    {
        $rule = Rule::unique('profiles', 'employee_id')->where(function ($query) {
            $query->whereIn('user_id', function ($sub) {
                $sub->select('id')->from('users')->whereNull('deleted_at');
            });
        });

        if ($ignoreUserId !== null) {
            $rule->ignore($ignoreUserId, 'user_id');
        }

        return $rule;
    }
}
