<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class DecodeHashid
{
    /**
     * Decode the {id} route parameter from a Hashid and rewrite it as the numeric id.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $value = $request->route('id');

        if ($value === null) {
            return $next($request);
        }

        $decoded = hashid_decode((string) $value);

        if ($decoded === null) {
            abort(404);
        }

        $request->route()->setParameter('id', $decoded);

        return $next($request);
    }
}
