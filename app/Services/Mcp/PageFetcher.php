<?php

namespace App\Services\Mcp;

use Illuminate\Contracts\Http\Kernel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Facade;

/**
 * Renders a site page inside the current PHP process, the way a visitor would get it.
 * Lets outline and audit tools read coded Blade pages as well as DB pages and blogs.
 */
class PageFetcher
{
    /**
     * @return array{path: string, status: int, html: string, main: string}
     */
    public static function fetch(string $pathOrUrl): array
    {
        $path = self::path($pathOrUrl);

        $app = app();
        $original = $app->bound('request') ? $app->make('request') : null;
        $kernel = $app->make(Kernel::class);

        try {
            $response = $kernel->handle(Request::create($path, 'GET'));
            $status = $response->getStatusCode();
            $html = (string) $response->getContent();
        } finally {
            if ($original) {
                $app->instance('request', $original);
                $app->make('url')->setRequest($original);
            }
            Facade::clearResolvedInstance('request');
        }

        return ['path' => $path, 'status' => $status, 'html' => $html, 'main' => self::main($html)];
    }

    /** "/x", "x" or "https://storagekeys.com/x" → "/x". Other hosts are refused. */
    public static function path(string $pathOrUrl): string
    {
        $pathOrUrl = trim($pathOrUrl);
        if (preg_match('#^https?://#i', $pathOrUrl)) {
            $host = strtolower((string) parse_url($pathOrUrl, PHP_URL_HOST));
            $own = [strtolower((string) parse_url(url('/'), PHP_URL_HOST)), 'storagekeys.com', 'www.storagekeys.com'];
            if (!in_array($host, $own, true)) {
                throw new McpException('Only pages on storagekeys.com can be checked.', 422);
            }
            $pathOrUrl = (string) (parse_url($pathOrUrl, PHP_URL_PATH) ?: '/');
        }

        return '/' . ltrim($pathOrUrl, '/');
    }

    /** The page body between the layout's header and footer. */
    public static function main(string $html): string
    {
        $start = strpos($html, '<!--sk-main-start-->');
        $end = strpos($html, '<!--sk-main-end-->');
        if ($start === false || $end === false || $end < $start) {
            return $html;
        }

        return substr($html, $start + 20, $end - $start - 20);
    }
}
