<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

/**
 * Class VerifyCsrfToken.
 */
class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        // Mobile/app Sanctum token auth (no CSRF cookie available)
        'api/auth/login',
        'api/auth/register',
        'customer-api/customer/login',
        'customer-api/customer/register',
        'customer-api/customer/forgot-password',
    ];

}
