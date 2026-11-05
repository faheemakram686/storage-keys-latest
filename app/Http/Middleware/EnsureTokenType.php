<?php

namespace App\Http\Middleware;

use App\Models\Contact;
use App\Models\Core\Auth\User;
use Closure;
use Illuminate\Http\Request;

/**
 * auth:sanctum accepts a token from any model, and employee and customer IDs overlap.
 * This keeps employee tokens on the employee API and customer tokens on the customer API.
 */
class EnsureTokenType
{
    private const MODELS = [
        'employee' => User::class,
        'customer' => Contact::class,
    ];

    public function handle(Request $request, Closure $next, string $type)
    {
        $model = self::MODELS[$type];

        if (!$request->user() instanceof $model) {
            return response()->json([
                'status' => false,
                'message' => 'This account cannot use this API.',
            ], 403);
        }

        return $next($request);
    }
}
