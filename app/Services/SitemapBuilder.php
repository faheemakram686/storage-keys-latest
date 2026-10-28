<?php

namespace App\Services;

use App\Models\Blog;
use App\Models\Page;
use App\Support\LocationPages;
use Throwable;

class SitemapBuilder
{
    public function staticPaths(): array
    {
        return array_merge([
            '/',
            '/storage-options',
            '/personal-storage',
            '/residential-storage',
            '/furniture-storage',
            '/box-storage',
            '/appliance-storage',
            '/business-storage',
            '/warehouse-storage',
            '/climate-controlled-storage',
            '/moving-services',
            '/luggage-storage',
            '/car-storage',
            '/short-term-storage',
            '/vehicle-storage',
            '/long-term-storage',
            '/office-storage',
            '/art-storage',
            '/equipment-storage',
            '/construction-storage',
            '/boat-storage',
            '/pharmaceutical-storage',
            '/shop',
            '/booking',
            '/blogs',
            '/about-us',
            '/contact-us',
            '/frequently-asked-questions',
            '/privacy-policy',
            '/security-policy',
            '/support-policy',
            '/cookie-policy',
            '/terms-of-service',
        ], array_map(static fn ($slug) => '/' . $slug, LocationPages::slugs()));
    }

    public function baseUrl(): string
    {
        $base = rtrim((string) config('app.canonical_url', 'https://storagekeys.com'), '/');
        if (str_starts_with($base, 'http://')) {
            $base = 'https://' . substr($base, 7);
        }
        if ($base === '' || !preg_match('#^https://#i', $base)) {
            $base = 'https://storagekeys.com';
        }

        return $base;
    }

    public function xml(): string
    {
        $base = $this->baseUrl();
        $lines = [
            '<?xml version="1.0" encoding="UTF-8"?>',
            '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">',
        ];

        foreach ($this->staticPaths() as $path) {
            $lines[] = $this->urlNode($base . ($path === '/' ? '/' : $path));
        }

        foreach ($this->blogs() as $blog) {
            $lastmod = optional($blog->updated_at ?? $blog->created_at)->toAtomString();
            $lines[] = $this->urlNode($base . '/blogs/' . $blog->slug, $lastmod);
        }

        $taken = array_fill_keys(array_map(static function ($path) {
            return trim($path, '/');
        }, $this->staticPaths()), true);

        foreach ($this->cmsPages() as $page) {
            $slug = trim((string) $page->slug, '/');
            if ($slug === '' || isset($taken[$slug])) {
                continue;
            }
            $lastmod = optional($page->updated_at ?? $page->created_at)->toAtomString();
            $lines[] = $this->urlNode($base . '/' . $slug, $lastmod);
        }

        $lines[] = '</urlset>';

        return implode("\n", $lines) . "\n";
    }

    public function blogs()
    {
        try {
            return Blog::query()
                ->where('is_deleted', 0)
                ->where('status', 1)
                ->whereNotNull('slug')
                ->where('slug', '!=', '')
                ->orderByDesc('updated_at')
                ->get(['slug', 'updated_at', 'created_at']);
        } catch (Throwable $e) {
            report($e);

            return collect();
        }
    }

    public function cmsPages()
    {
        try {
            return Page::query()
                ->published()
                ->orderByDesc('updated_at')
                ->get(['slug', 'updated_at', 'created_at']);
        } catch (Throwable $e) {
            report($e);

            return collect();
        }
    }

    private function urlNode(string $loc, ?string $lastmod = null): string
    {
        $xml = "  <url>\n";
        $xml .= '    <loc>' . htmlspecialchars($loc, ENT_XML1) . "</loc>\n";
        if ($lastmod) {
            $xml .= '    <lastmod>' . htmlspecialchars($lastmod, ENT_XML1) . "</lastmod>\n";
        }
        $xml .= '  </url>';

        return $xml;
    }
}
