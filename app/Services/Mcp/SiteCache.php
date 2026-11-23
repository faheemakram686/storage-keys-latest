<?php

namespace App\Services\Mcp;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

/**
 * The site renders pages on every request and builds the sitemap live, so there is no page cache
 * to purge after a write. touch() only invalidates what the MCP tools themselves cache (audit results).
 */
class SiteCache
{
    private const VERSION_KEY = 'mcp:cache_version';

    public static function version(): int
    {
        return (int) Cache::get(self::VERSION_KEY, 1);
    }

    /** Key for MCP-cached data; changes after every content write. */
    public static function key(string $name): string
    {
        return 'mcp:' . self::version() . ':' . $name;
    }

    public static function touch(): void
    {
        Cache::forever(self::VERSION_KEY, self::version() + 1);
    }

    public static function clear(?string $url = null): array
    {
        Artisan::call('view:clear');
        Artisan::call('cache:clear');

        $result = [
            'message' => 'Cleared compiled views and the application cache. Pages and the sitemap are generated live, so the next visit shows current content.',
            'cleared' => ['views', 'application cache'],
        ];

        if ($url !== null && trim($url) !== '') {
            $full = preg_match('#^https?://#i', $url) ? $url : url('/' . ltrim($url, '/'));
            if (parse_url($full, PHP_URL_HOST) !== parse_url(url('/'), PHP_URL_HOST)) {
                throw new McpException('url must be on this site.', 422);
            }
            try {
                $status = Http::timeout(20)->withoutRedirecting()->get($full)->status();
            } catch (\Throwable $e) {
                $status = null;
            }
            $result['checked_url'] = $full;
            $result['status'] = $status;
            $result['note'] = 'If a CDN such as Cloudflare caches the site, purge this URL there too.';
        }

        return $result;
    }
}
