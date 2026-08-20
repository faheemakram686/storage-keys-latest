<?php

namespace App\Http\Middleware;

use App\Models\Contact;
use App\Services\Contact\ContactRoleSyncService;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ContactPermissionMiddleware
{
    public function handle(Request $request, Closure $next, ...$permissions)
    {
        /** @var Contact|null $contact */
        $contact = Auth::guard('contact')->user();

        if (!$contact) {
            abort(401);
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

        $sync = resolve(ContactRoleSyncService::class);

        if (!$sync->contactHasAnyPermission($contact, $permissions)) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json(['message' => 'Unauthorized'], 403);
            }

            abort(403, 'You do not have permission to perform this action.');
        }

        return $next($request);
    }
}
