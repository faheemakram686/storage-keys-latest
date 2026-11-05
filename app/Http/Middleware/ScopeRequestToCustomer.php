<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

/**
 * The customer app sends its customer ID as "id" or "customer_id". These always
 * come from the logged-in contact, so one customer cannot ask for another's data.
 */
class ScopeRequestToCustomer
{
    public function handle(Request $request, Closure $next)
    {
        $customerId = $request->user()->customer_id;

        $request->merge(['id' => $customerId, 'customer_id' => $customerId]);

        return $next($request);
    }
}
