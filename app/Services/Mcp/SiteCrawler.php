<?php

namespace App\Services\Mcp;

use App\Models\Page;
use App\Services\SitemapBuilder;
use App\Support\LocationPages;
use Illuminate\Support\Facades\Cache;

/**
 * Renders every page in the sitemap (coded Blade pages, DB pages, blog posts) and extracts what the
 * audit tools need. Results are cached until the next content write (SiteCache::touch) or 30 minutes.
 */
class SiteCrawler
{
    private const TTL = 1800;

    /** @return list<array{path: string, kind: string}> */
    public function inventory(): array
    {
        $sitemap = app(SitemapBuilder::class);
        $items = [];
        $seen = [];

        foreach ($sitemap->staticPaths() as $path) {
            $slug = trim($path, '/');
            $kind = LocationPages::isCmsEditable($slug) && Page::query()->published()->where('slug', $slug)->exists() ? 'db_page' : 'coded_page';
            $items[] = ['path' => $path, 'kind' => $kind];
            $seen[$slug] = true;
        }
        foreach ($sitemap->cmsPages() as $page) {
            $slug = trim((string) $page->slug, '/');
            if ($slug !== '' && !isset($seen[$slug])) {
                $items[] = ['path' => '/' . $slug, 'kind' => 'db_page'];
                $seen[$slug] = true;
            }
        }
        foreach ($sitemap->blogs() as $blog) {
            $items[] = ['path' => '/blogs/' . $blog->slug, 'kind' => 'blog'];
        }

        return $items;
    }

    /** Analysed pages; $only = one path/URL, else the whole site. */
    public function pages(?string $only = null): array
    {
        if ($only !== null && trim($only) !== '') {
            $path = PageFetcher::path($only);
            $kind = 'coded_page';
            foreach ($this->inventory() as $item) {
                if ($item['path'] === $path) {
                    $kind = $item['kind'];
                }
            }

            return [$this->page($path, $kind)];
        }

        return Cache::remember(SiteCache::key('crawl:all'), self::TTL, function () {
            return array_map(fn ($item) => $this->page($item['path'], $item['kind']), $this->inventory());
        });
    }

    public function page(string $path, string $kind = 'coded_page'): array
    {
        return Cache::remember(SiteCache::key('crawl:' . md5($path)), self::TTL, fn () => $this->analyse($path, $kind));
    }

    private function analyse(string $path, string $kind): array
    {
        $fetched = PageFetcher::fetch($path);
        $html = $fetched['html'];
        $main = $fetched['main'];
        $meta = $fetched['status'] === 200 ? Html::meta($html) : ['title' => null, 'description' => null, 'robots' => null, 'canonical' => null];

        $links = [];
        foreach (Html::links($main) as $link) {
            $target = self::internalPath($link['href']);
            if ($target !== null && $target !== $path) {
                $links[] = ['target' => $target, 'text' => $link['text']];
            }
        }

        return [
            'path' => $path,
            'kind' => $kind,
            'status' => $fetched['status'],
            'title' => $meta['title'],
            'description' => $meta['description'],
            'robots' => $meta['robots'],
            'canonical' => $meta['canonical'],
            'headings' => Html::headings($main),
            'words' => Html::wordCount($main),
            'links' => $links,
            'images' => array_map(fn ($img) => $img + self::imageFile($img['src']), Html::images($main)),
            'schema_types' => array_values(array_unique(array_filter(array_map(
                fn ($n) => is_array($n['@type'] ?? null) ? implode('/', $n['@type']) : ($n['@type'] ?? null),
                Html::schemaNodes($html)
            )))),
            'html_bytes' => strlen($html),
            'blocking' => self::renderBlocking($html),
        ];
    }

    /** Internal link target as a path without query or fragment; null for external, mailto, tel, #. */
    public static function internalPath(string $href): ?string
    {
        $href = trim($href);
        if ($href === '' || $href[0] === '#' || preg_match('#^(mailto|tel|javascript|data|whatsapp):#i', $href)) {
            return null;
        }
        if (preg_match('#^(https?:)?//#i', $href)) {
            $host = strtolower((string) parse_url(str_starts_with($href, '//') ? 'https:' . $href : $href, PHP_URL_HOST));
            $own = [strtolower((string) parse_url(url('/'), PHP_URL_HOST)), 'storagekeys.com', 'www.storagekeys.com'];
            if (!in_array($host, $own, true)) {
                return null;
            }
            $href = (string) (parse_url(str_starts_with($href, '//') ? 'https:' . $href : $href, PHP_URL_PATH) ?: '/');
        } elseif ($href[0] !== '/') {
            return null;
        }
        $path = '/' . trim((string) parse_url($href, PHP_URL_PATH), '/');

        return $path === '/' ? '/' : rtrim($path, '/');
    }

    /** Local image file facts: format and size on disk. */
    public static function imageFile(string $src): array
    {
        $path = self::internalPath($src) ?? (str_starts_with($src, 'http') ? null : '/' . ltrim($src, '/'));
        $ext = strtolower(pathinfo((string) parse_url($src, PHP_URL_PATH), PATHINFO_EXTENSION));
        $info = ['format' => $ext ?: null, 'bytes' => null, 'local' => false];
        if ($path !== null && !str_contains($path, '..')) {
            $file = public_path(rawurldecode(ltrim($path, '/')));
            if (is_file($file)) {
                $info['bytes'] = filesize($file);
                $info['local'] = true;
            }
        }

        return $info;
    }

    /** Scripts without async/defer and stylesheets in <head>. */
    private static function renderBlocking(string $html): array
    {
        $head = preg_match('#<head\b.*?</head>#is', $html, $m) ? $m[0] : '';
        $scripts = [];
        $styles = [];
        if (preg_match_all('#<script\b([^>]*)\bsrc=["\']([^"\']+)["\']([^>]*)>#i', $head, $mm, PREG_SET_ORDER)) {
            foreach ($mm as $s) {
                $attrs = $s[1] . ' ' . $s[3];
                if (!preg_match('/\b(async|defer)\b|type=["\']module["\']/i', $attrs)) {
                    $scripts[] = $s[2];
                }
            }
        }
        if (preg_match_all('#<link\b[^>]*rel=["\']stylesheet["\'][^>]*>#i', $head, $mm)) {
            foreach ($mm[0] as $tag) {
                if (!preg_match('/media=["\']print["\']|onload=/i', $tag) && preg_match('/href=["\']([^"\']+)["\']/i', $tag, $h)) {
                    $styles[] = $h[1];
                }
            }
        }

        return ['scripts' => $scripts, 'styles' => $styles];
    }
}
