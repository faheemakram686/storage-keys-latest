<?php

namespace App\Services\Mcp;

use App\Models\ContentRevision;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * robots.txt is served by Laravel (route /robots.txt): the text saved with update_robots,
 * or resources/seo/robots.txt until one is saved. Not a file in public/, so deploys never clash with edits.
 */
class RobotsTxt
{
    private const KEY = 'robots_txt';
    private const DIRECTIVES = ['user-agent', 'allow', 'disallow', 'sitemap', 'crawl-delay', 'host', 'clean-param'];

    public static function default(): string
    {
        return (string) file_get_contents(resource_path('seo/robots.txt'));
    }

    public static function content(): string
    {
        if (Schema::hasTable('app_settings')) {
            $saved = DB::table('app_settings')->where('key', self::KEY)->value('value');
            if (is_string($saved) && trim($saved) !== '') {
                return $saved;
            }
        }

        return self::default();
    }

    public static function sitemapUrl(): string
    {
        return app(\App\Services\SitemapBuilder::class)->baseUrl() . '/sitemap.xml';
    }

    /**
     * @return array{errors: list<string>, warnings: list<string>}
     */
    public static function validate(string $content): array
    {
        $errors = [];
        $warnings = [];
        $agent = null;
        $groups = [];
        $sitemaps = [];

        foreach (preg_split('/\r\n|\r|\n/', $content) as $i => $line) {
            $clean = trim(preg_replace('/#.*$/', '', $line));
            if ($clean === '') {
                continue;
            }
            if (!preg_match('/^([A-Za-z-]+)\s*:\s*(.*)$/', $clean, $m)) {
                $errors[] = 'Line ' . ($i + 1) . ' is not "Directive: value": ' . $clean;
                continue;
            }
            $key = strtolower($m[1]);
            $value = trim($m[2]);
            if (!in_array($key, self::DIRECTIVES, true)) {
                $warnings[] = 'Line ' . ($i + 1) . ': unknown directive "' . $m[1] . '" (search engines ignore it).';
                continue;
            }
            if ($key === 'user-agent') {
                $agent = strtolower($value);
                $groups[$agent] ??= [];
            } elseif (in_array($key, ['allow', 'disallow'], true)) {
                if ($agent === null) {
                    $errors[] = 'Line ' . ($i + 1) . ': ' . $m[1] . ' comes before any User-agent line.';
                } else {
                    $groups[$agent][] = [$key, $value];
                }
            } elseif ($key === 'sitemap') {
                if (!preg_match('#^https?://#i', $value)) {
                    $errors[] = 'Line ' . ($i + 1) . ': Sitemap must be a full URL.';
                }
                $sitemaps[] = $value;
            }
        }

        if ($groups === []) {
            $errors[] = 'There is no User-agent line.';
        }
        foreach ($groups['*'] ?? [] as [$key, $value]) {
            if ($key === 'disallow' && $value === '/') {
                $errors[] = '"User-agent: *" with "Disallow: /" blocks the whole site from search engines.';
            }
        }
        $want = self::sitemapUrl();
        if (!in_array($want, $sitemaps, true)) {
            $errors[] = 'robots.txt must list the sitemap: "Sitemap: ' . $want . '".';
        }
        // Plain path rules that would hide pages listed in the sitemap.
        $paths = app(\App\Services\SitemapBuilder::class)->staticPaths();
        foreach ($groups['*'] ?? [] as [$key, $value]) {
            if ($key !== 'disallow' || $value === '' || $value === '/' || strpbrk($value, '*?$') !== false) {
                continue;
            }
            $blocked = array_values(array_filter($paths, fn ($p) => str_starts_with($p, $value)));
            if ($blocked) {
                $warnings[] = 'Disallow: ' . $value . ' blocks sitemap page(s): ' . implode(', ', array_slice($blocked, 0, 5)) . '.';
            }
        }

        return ['errors' => array_values(array_unique($errors)), 'warnings' => array_values(array_unique($warnings))];
    }

    public static function save(string $content): void
    {
        $before = self::content();
        $content = rtrim(str_replace(["\r\n", "\r"], "\n", $content)) . "\n";
        DB::table('app_settings')->updateOrInsert(['key' => self::KEY], ['value' => $content, 'description' => 'robots.txt (edited with the MCP update_robots tool)', 'updated_at' => now(), 'created_at' => now()]);

        if (Revisions::tableReady()) {
            ContentRevision::create([
                'entity_type' => 'robots',
                'entity_id' => 0,
                'event' => 'updated',
                'source' => McpContext::source(),
                'tool' => McpContext::$tool,
                'summary' => 'robots.txt changed',
                'changed' => ['content'],
                'before' => ['content' => $before],
                'after' => ['content' => $content],
                'created_at' => now(),
            ]);
        }
        SiteCache::touch();
    }
}
