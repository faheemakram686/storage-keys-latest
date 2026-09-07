<?php

namespace App\Http\Middleware;

use App\Models\UrlRedirect;
use Closure;
use Illuminate\Http\Request;

/**
 * Applies active URL redirects for frontend GET requests only.
 * Skips /api, /admin, and asset-like paths so existing apps stay untouched.
 */
class ApplyUrlRedirects
{
    public function handle(Request $request, Closure $next)
    {
        if (!$request->isMethod('GET') && !$request->isMethod('HEAD')) {
            return $next($request);
        }

        $path = $request->getPathInfo();
        if (
            str_starts_with($path, '/api')
            || str_starts_with($path, '/admin')
            || str_starts_with($path, '/storage')
            || str_starts_with($path, '/sk-assets')
            || str_starts_with($path, '/vendor')
        ) {
            return $next($request);
        }

        try {
            if (!\Illuminate\Support\Facades\Schema::hasTable('url_redirects')) {
                return $next($request);
            }

            $normalized = UrlRedirect::normalizePath($path);
            $redirect = UrlRedirect::query()
                ->where('is_active', 1)
                ->where('from_path', $normalized)
                ->first();

            if ($redirect) {
                $code = in_array((int) $redirect->status_code, [301, 302, 307, 308], true)
                    ? (int) $redirect->status_code
                    : 301;

                return redirect()->to($redirect->to_url, $code);
            }
        } catch (\Throwable $e) {
            // Never break the site if redirects table/query fails.
        }

        return $next($request);
    }
}
