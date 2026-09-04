<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class VerifyMcpBlogToken
{
    public function handle(Request $request, Closure $next)
    {
        $configured = (string) config('services.mcp_blog.token', '');

        if ($configured === '') {
            return response()->json([
                'success' => false,
                'message' => 'MCP blog API token is not configured.',
            ], 503);
        }

        $provided = $request->bearerToken()
            ?: $request->header('X-MCP-Token')
            ?: $request->header('X-MCP-TOKEN');

        if (!is_string($provided) || !hash_equals($configured, $provided)) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized.',
            ], 401);
        }

        return $next($request);
    }
}
