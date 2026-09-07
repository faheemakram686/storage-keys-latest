<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

/**
 * Phase 3: link map, basic content audits, sitemap/robots checks, stale posts.
 * Read-mostly; does not change public page design.
 */
class McpAuditController extends Controller
{
    public function internalLinks(Request $request)
    {
        $limitBlogs = min(100, max(1, (int) $request->query('limit', 50)));
        $blogs = Blog::query()
            ->where('is_deleted', 0)
            ->orderByDesc('id')
            ->limit($limitBlogs)
            ->get(['id', 'title', 'slug', 'description']);

        $sitePages = app(McpSiteController::class)->pages()->getData(true)['pages'] ?? [];
        $knownPaths = collect($sitePages)->pluck('path')->map(fn ($p) => rtrim($p, '/') ?: '/')->all();
        $blogSlugs = $blogs->pluck('slug')->all();

        $edges = [];
        $inbound = [];

        foreach ($blogs as $blog) {
            $from = '/blogs/' . $blog->slug;
            $links = $this->extractHrefs((string) $blog->description);
            foreach ($links as $href) {
                $normalized = $this->normalizeInternalHref($href);
                if ($normalized === null) {
                    continue;
                }
                $edges[] = [
                    'from' => $from,
                    'from_blog_id' => $blog->id,
                    'to' => $normalized,
                    'anchor_sample' => Str::limit($href['text'] ?? '', 80),
                ];
                $inbound[$normalized] = ($inbound[$normalized] ?? 0) + 1;
            }
        }

        $orphans = [];
        foreach ($blogs as $blog) {
            $path = '/blogs/' . $blog->slug;
            if (($inbound[$path] ?? 0) === 0) {
                $orphans[] = [
                    'id' => $blog->id,
                    'slug' => $blog->slug,
                    'title' => $blog->title,
                    'url' => url($path),
                ];
            }
        }

        return response()->json([
            'success' => true,
            'edge_count' => count($edges),
            'edges' => array_slice($edges, 0, 500),
            'orphan_blogs' => $orphans,
            'known_static_paths' => $knownPaths,
            'blog_count_scanned' => $blogs->count(),
            'note' => 'Internal links parsed from blog HTML only (static Blade pages are not crawled).',
        ]);
    }

    public function checkBrokenLinks(Request $request)
    {
        $limit = min(30, max(1, (int) $request->input('limit', 15)));
        $blogs = Blog::query()
            ->where('is_deleted', 0)
            ->orderByDesc('id')
            ->limit(20)
            ->get(['id', 'slug', 'description']);

        $checked = [];
        $broken = [];
        $seen = [];

        foreach ($blogs as $blog) {
            foreach ($this->extractHrefs((string) $blog->description) as $link) {
                $url = $link['href'] ?? '';
                if ($url === '' || isset($seen[$url]) || count($checked) >= $limit) {
                    continue;
                }
                if (!preg_match('#^https?://#i', $url)) {
                    if (str_starts_with($url, '/')) {
                        $url = url($url);
                    } else {
                        continue;
                    }
                }
                $seen[$url] = true;
                $status = null;
                $ok = false;
                try {
                    $res = Http::timeout(8)
                        ->withHeaders(['User-Agent' => 'StorageKeys-MCP-LinkCheck/1.0'])
                        ->head($url);
                    $status = $res->status();
                    if ($status >= 400 || $status === 405) {
                        $res = Http::timeout(8)->withHeaders(['User-Agent' => 'StorageKeys-MCP-LinkCheck/1.0'])->get($url);
                        $status = $res->status();
                    }
                    $ok = $status > 0 && $status < 400;
                } catch (\Throwable $e) {
                    $status = 0;
                    $ok = false;
                }
                $row = [
                    'url' => $url,
                    'status' => $status,
                    'ok' => $ok,
                    'found_in_blog_id' => $blog->id,
                    'found_in_slug' => $blog->slug,
                ];
                $checked[] = $row;
                if (!$ok) {
                    $broken[] = $row;
                }
            }
            if (count($checked) >= $limit) {
                break;
            }
        }

        return response()->json([
            'success' => true,
            'checked_count' => count($checked),
            'broken_count' => count($broken),
            'broken' => $broken,
            'checked' => $checked,
        ]);
    }

    public function auditContent(Request $request)
    {
        $blogs = Blog::query()
            ->where('is_deleted', 0)
            ->orderByDesc('id')
            ->limit(100)
            ->get(['id', 'title', 'slug', 'description', 'meta_title', 'meta_description', 'status', 'updated_at']);

        $thin = [];
        $missingMeta = [];
        $titles = [];

        foreach ($blogs as $blog) {
            $text = trim(preg_replace('/\s+/', ' ', strip_tags((string) $blog->description)));
            $words = $text === '' ? 0 : count(preg_split('/\s+/', $text));
            if ($words < 250) {
                $thin[] = [
                    'id' => $blog->id,
                    'slug' => $blog->slug,
                    'title' => $blog->title,
                    'word_count' => $words,
                ];
            }
            if (empty($blog->meta_title) || empty($blog->meta_description)) {
                $missingMeta[] = [
                    'id' => $blog->id,
                    'slug' => $blog->slug,
                    'title' => $blog->title,
                    'has_meta_title' => !empty($blog->meta_title),
                    'has_meta_description' => !empty($blog->meta_description),
                ];
            }
            $key = Str::lower(trim((string) $blog->title));
            $titles[$key][] = ['id' => $blog->id, 'slug' => $blog->slug, 'title' => $blog->title];
        }

        $duplicates = [];
        foreach ($titles as $group) {
            if (count($group) > 1) {
                $duplicates[] = $group;
            }
        }

        // Simple cannibalization heuristic: high title similarity pairs
        $cannibalization = [];
        $list = $blogs->values();
        for ($i = 0; $i < $list->count(); $i++) {
            for ($j = $i + 1; $j < $list->count(); $j++) {
                similar_text(Str::lower($list[$i]->title), Str::lower($list[$j]->title), $pct);
                if ($pct >= 75) {
                    $cannibalization[] = [
                        'similarity_pct' => round($pct, 1),
                        'a' => ['id' => $list[$i]->id, 'slug' => $list[$i]->slug, 'title' => $list[$i]->title],
                        'b' => ['id' => $list[$j]->id, 'slug' => $list[$j]->slug, 'title' => $list[$j]->title],
                    ];
                }
            }
        }

        return response()->json([
            'success' => true,
            'scanned' => $blogs->count(),
            'thin_content' => $thin,
            'missing_seo_meta' => $missingMeta,
            'duplicate_titles' => $duplicates,
            'possible_cannibalization' => array_slice($cannibalization, 0, 50),
        ]);
    }

    public function validateSitemapRobots()
    {
        $issues = [];
        $ok = [];

        $sitemapUrl = url('/sitemap.xml');
        $robotsPath = public_path('robots.txt');

        try {
            $res = Http::timeout(10)->get($sitemapUrl);
            if (!$res->successful()) {
                $issues[] = 'sitemap.xml HTTP ' . $res->status();
            } else {
                $body = $res->body();
                if (!str_contains($body, '<urlset') && !str_contains($body, '<sitemapindex')) {
                    $issues[] = 'sitemap.xml does not look like valid XML sitemap';
                } else {
                    $ok[] = 'sitemap.xml reachable';
                }
            }
        } catch (\Throwable $e) {
            $issues[] = 'sitemap.xml fetch failed: ' . $e->getMessage();
        }

        if (!is_file($robotsPath)) {
            $issues[] = 'public/robots.txt missing';
        } else {
            $robots = (string) file_get_contents($robotsPath);
            $ok[] = 'robots.txt present';
            if (!preg_match('/sitemap:\s*\S+/i', $robots)) {
                $issues[] = 'robots.txt has no Sitemap: directive';
            } else {
                $ok[] = 'robots.txt references a Sitemap';
            }
        }

        return response()->json([
            'success' => true,
            'ok' => $ok,
            'issues' => $issues,
            'sitemap_url' => $sitemapUrl,
            'robots_url' => url('/robots.txt'),
        ]);
    }

    public function stalePosts(Request $request)
    {
        $days = min(730, max(30, (int) $request->query('days', 180)));
        $cutoff = Carbon::now()->subDays($days);

        $blogs = Blog::query()
            ->where('is_deleted', 0)
            ->where(function ($q) use ($cutoff) {
                $q->whereNull('last_reviewed_at')
                    ->orWhere('last_reviewed_at', '<', $cutoff);
            })
            ->orderBy('last_reviewed_at')
            ->limit(50)
            ->get(['id', 'title', 'slug', 'status', 'updated_at', 'last_reviewed_at']);

        $items = $blogs->map(function (Blog $b) {
            return [
                'id' => $b->id,
                'title' => $b->title,
                'slug' => $b->slug,
                'status' => (int) $b->getRawOriginal('status'),
                'updated_at' => optional($b->updated_at)->toDateTimeString(),
                'last_reviewed_at' => optional($b->last_reviewed_at)->toDateTimeString(),
                'url' => url('/blogs/' . $b->slug),
            ];
        });

        return response()->json([
            'success' => true,
            'days' => $days,
            'count' => $items->count(),
            'stale_posts' => $items,
        ]);
    }

    public function markReviewed(Request $request, $idOrSlug)
    {
        $blog = Blog::query()->where('is_deleted', 0);
        $blog = is_numeric($idOrSlug)
            ? $blog->where('id', (int) $idOrSlug)->first()
            : $blog->where('slug', (string) $idOrSlug)->first();

        if (!$blog) {
            return response()->json(['success' => false, 'message' => 'Blog not found.'], 404);
        }

        $blog->last_reviewed_at = Carbon::now();
        $blog->save();

        return response()->json([
            'success' => true,
            'message' => 'Marked reviewed',
            'id' => $blog->id,
            'slug' => $blog->slug,
            'last_reviewed_at' => optional($blog->last_reviewed_at)->toDateTimeString(),
        ]);
    }

    /**
     * @return list<array{href: string, text: string}>
     */
    private function extractHrefs(string $html): array
    {
        $out = [];
        if ($html === '') {
            return $out;
        }
        if (preg_match_all('/<a\s[^>]*href=["\']([^"\']+)["\'][^>]*>(.*?)<\/a>/is', $html, $m, PREG_SET_ORDER)) {
            foreach ($m as $match) {
                $out[] = [
                    'href' => html_entity_decode($match[1]),
                    'text' => trim(strip_tags($match[2])),
                ];
            }
        }

        return $out;
    }

    private function normalizeInternalHref(array $link): ?string
    {
        $href = $link['href'] ?? '';
        if ($href === '' || str_starts_with($href, '#') || str_starts_with($href, 'mailto:') || str_starts_with($href, 'tel:')) {
            return null;
        }

        if (preg_match('#^https?://#i', $href)) {
            $host = parse_url($href, PHP_URL_HOST);
            $appHost = parse_url(url('/'), PHP_URL_HOST);
            $canonHost = parse_url((string) config('app.canonical_url'), PHP_URL_HOST);
            if ($host && $host !== $appHost && $host !== $canonHost && $host !== 'storagekeys.com' && $host !== 'www.storagekeys.com') {
                return null;
            }
            $path = parse_url($href, PHP_URL_PATH) ?: '/';
        } elseif (str_starts_with($href, '/')) {
            $path = $href;
        } else {
            return null;
        }

        $path = '/' . ltrim($path, '/');
        if ($path !== '/') {
            $path = rtrim($path, '/');
        }

        return $path;
    }
}
