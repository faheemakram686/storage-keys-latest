<?php

namespace App\Http\Middleware;

use App\Services\Core\Auth\UserRoleSyncService;
use Closure;
use Illuminate\Http\Request;

/**
 * Enforce Admin Portal (type=admin) permission names on /admin routes.
 * App Admin always passes. Uses union of all role permissions.
 */
class PortalPermissionMiddleware
{
    public function handle(Request $request, Closure $next, ...$permissions)
    {
        $user = auth()->user();

        if (!$user) {
            abort(401);
        }

        if ($user->isAppAdmin()) {
            return $next($request);
        }

        $permissions = collect($permissions)
            ->flatMap(fn ($p) => explode('|', $p))
            ->map(fn ($p) => trim($p))
            ->filter()
            ->unique()
            ->values()
            ->all();

        if (empty($permissions)) {
            return $next($request);
        }

        $sync = resolve(UserRoleSyncService::class);

        if (!$sync->userHasAnyPermission($user, $permissions)) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json(['message' => 'Unauthorized'], 403);
            }

            abort(403, 'You do not have permission to perform this action.');
        }

        return $next($request);
    }
}
