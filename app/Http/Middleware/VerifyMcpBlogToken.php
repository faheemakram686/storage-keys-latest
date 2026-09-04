<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class VerifyMcpBlogToken
{
    public function handle(Request $request, Closure $next)
    {
        $configured = $this->configuredToken();

        if ($configured === '') {
            return response()->json([
                'success' => false,
                'message' => 'MCP blog API token is not configured. Set MCP_BLOG_TOKEN in the live .env (project root), then run: php artisan config:clear',
            ], 503);
        }

        // Claude.ai custom connectors may use the token in the URL path
        // when request-header auth is not available on the account.
        $routeToken = $request->route('mcpToken');

        $provided = $request->bearerToken()
            ?: $request->header('X-MCP-Token')
            ?: $request->header('X-MCP-TOKEN')
            ?: (is_string($routeToken) ? $routeToken : null);

        if (!is_string($provided) || !hash_equals($configured, $provided)) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized.',
            ], 401);
        }

        return $next($request);
    }

    private function configuredToken(): string
    {
        $candidates = [
            config('services.mcp_blog.token'),
            $_ENV['MCP_BLOG_TOKEN'] ?? null,
            $_SERVER['MCP_BLOG_TOKEN'] ?? null,
            getenv('MCP_BLOG_TOKEN') ?: null,
        ];

        foreach ($candidates as $value) {
            if (is_string($value)) {
                $value = trim($value);
                if ($value !== '') {
                    return $value;
                }
            }
        }

        return '';
    }
}
