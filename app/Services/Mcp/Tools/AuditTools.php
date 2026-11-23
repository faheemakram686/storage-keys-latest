<?php

namespace App\Services\Mcp\Tools;

use App\Services\Mcp\McpException;
use App\Services\Mcp\SiteCrawler;

/**
 * Phase 3: read-only audits over every page in the sitemap (coded pages, DB pages, blog posts).
 * Pages are rendered in-process as a visitor gets them; results are cached until the next content write.
 */
class AuditTools extends Toolset
{
    private const TITLE_MIN = 30;
    private const TITLE_MAX = 60;
    private const DESC_MIN = 70;
    private const DESC_MAX = 160;
    private const IMAGE_MAX_BYTES = 200 * 1024;
    private const GENERIC_ANCHORS = ['click here', 'here', 'read more', 'learn more', 'more', 'this', 'link', 'this page', 'find out more', 'view more', 'see more'];
    private const STOPWORDS = ['a', 'an', 'and', 'the', 'in', 'of', 'for', 'to', 'with', 'on', 'at', 'by', 'or', 'your', 'our', 'is', 'how', 'what', 'best', 'uae', 'storagekeys', 'storage', 'keys', '&', '|', '-'];

    public function __construct(private SiteCrawler $crawler)
    {
    }

    public function definitions(): array
    {
        $url = ['type' => 'string', 'description' => 'Optional page path or storagekeys.com URL; whole site when omitted'];
        $limit = ['type' => 'integer', 'minimum' => 1, 'maximum' => 200];

        return [
            $this->tool('audit_seo', 'SEO score (0-100) and issues per page: status, title and description length, H1 count, heading order, thin content, image alt text, robots, schema. Covers coded pages, DB pages and blog posts. Worst pages first.', ['url' => $url, 'limit' => $limit]),
            $this->tool('audit_meta', 'Titles and meta descriptions across the site: missing, duplicate, too long (title > 60, description > 160) or too short (title < 30, description < 70).'),
            $this->tool('audit_headings', 'Heading structure per page: H1 count (should be 1), skipped levels, empty headings.', ['url' => $url]),
            $this->tool('audit_images', 'Images in page content: missing alt text, files over 200 KB, formats other than WebP/SVG/AVIF, missing local files.', ['url' => $url, 'limit' => $limit]),
            $this->tool('audit_performance', 'Page weight: HTML size, image count and total local image size, largest images, render-blocking scripts and stylesheets in <head>. Heaviest pages first.', ['url' => $url, 'limit' => $limit]),
            $this->tool('audit_cannibalisation', 'Pages and posts that compete for the same search terms (overlapping title keywords or identical H1).', [
                'min_overlap' => ['type' => 'number', 'minimum' => 0.3, 'maximum' => 1, 'description' => 'Keyword overlap that counts as competing (default 0.6)'],
            ]),
            $this->tool('audit_thin_content', 'Pages and posts with fewer words in the main content than min_words (default 600).', [
                'min_words' => ['type' => 'integer', 'minimum' => 50],
            ]),
            $this->tool('seo_report', 'Site-wide summary of every audit: page counts, average score, worst pages, meta, heading, image, thin-content, orphan and cannibalisation counts.'),
            $this->tool('anchor_text_report', 'Internal link anchors (in page content) pointing to each URL, generic anchors like "click here", and anchors reused for different targets.', [
                'target_url' => ['type' => 'string', 'description' => 'Only links to this path/URL'],
            ]),
            $this->tool('find_orphan_pages', 'Pages and posts that no other page links to from its content (header and footer menus are not counted).'),
        ];
    }

    public function call(string $name, array $a): array
    {
        switch ($name) {
            case 'audit_seo':
                return $this->seo($a);
            case 'audit_meta':
                return $this->meta();
            case 'audit_headings':
                return $this->headings($a);
            case 'audit_images':
                return $this->images($a);
            case 'audit_performance':
                return $this->performance($a);
            case 'audit_cannibalisation':
                return $this->cannibalisation($a);
            case 'audit_thin_content':
                return $this->thin($a);
            case 'seo_report':
                return $this->report();
            case 'anchor_text_report':
                return $this->anchors($a);
            case 'find_orphan_pages':
                return $this->orphans();
        }

        throw new McpException('Unknown tool: ' . $name, 404);
    }

    private function pages(array $a = []): array
    {
        return $this->crawler->pages($this->str($a, 'url'));
    }

    private function ok(array $p): bool
    {
        return $p['status'] === 200;
    }

    // ---------------------------------------------------------------- checks

    /** @return list<array{severity: string, issue: string}> */
    private function issues(array $p): array
    {
        if (!$this->ok($p)) {
            return [['severity' => 'error', 'issue' => 'Page returns HTTP ' . $p['status'] . '.']];
        }
        $out = [];
        $add = function (string $severity, string $issue) use (&$out) {
            $out[] = ['severity' => $severity, 'issue' => $issue];
        };

        $title = (string) $p['title'];
        $len = mb_strlen($title);
        if ($len === 0) {
            $add('error', 'Missing <title>.');
        } elseif ($len > self::TITLE_MAX) {
            $add('warning', "Title is {$len} characters (over " . self::TITLE_MAX . ').');
        } elseif ($len < self::TITLE_MIN) {
            $add('warning', "Title is {$len} characters (under " . self::TITLE_MIN . ').');
        }

        $desc = (string) $p['description'];
        $len = mb_strlen($desc);
        if ($len === 0) {
            $add('error', 'Missing meta description.');
        } elseif ($len > self::DESC_MAX) {
            $add('warning', "Meta description is {$len} characters (over " . self::DESC_MAX . ').');
        } elseif ($len < self::DESC_MIN) {
            $add('warning', "Meta description is {$len} characters (under " . self::DESC_MIN . ').');
        }

        foreach ($this->headingIssues($p) as $issue) {
            $add(str_contains($issue, 'H1 headings') ? 'error' : 'warning', $issue);
        }
        if ($p['words'] < 300) {
            $add('warning', 'Only ' . $p['words'] . ' words of content.');
        }
        $noAlt = count(array_filter($p['images'], fn ($i) => $i['alt'] === null));
        if ($noAlt) {
            $add('error', $noAlt . ' image(s) without alt text.');
        }
        if ($p['robots'] && str_contains(strtolower($p['robots']), 'noindex')) {
            $add('warning', 'Page is noindex (' . $p['robots'] . ').');
        }
        if ($p['schema_types'] === []) {
            $add('info', 'No structured data (JSON-LD).');
        }

        return $out;
    }

    private function headingIssues(array $p): array
    {
        $issues = [];
        $h1 = count(array_filter($p['headings'], fn ($h) => $h['level'] === 1));
        if ($h1 !== 1) {
            $issues[] = $h1 . ' H1 headings (should be exactly 1).';
        }
        $prev = 0;
        foreach ($p['headings'] as $h) {
            if ($h['text'] === '') {
                $issues[] = 'Empty H' . $h['level'] . '.';
            }
            if ($prev && $h['level'] > $prev + 1) {
                $issues[] = 'Skipped level: H' . $prev . ' then H' . $h['level'] . ' ("' . mb_substr($h['text'], 0, 60) . '").';
            }
            $prev = $h['level'];
        }

        return $issues;
    }

    private function score(array $issues): int
    {
        $weights = ['error' => 15, 'warning' => 5, 'info' => 1];

        return max(0, 100 - array_sum(array_map(fn ($i) => $weights[$i['severity']], $issues)));
    }

    // ---------------------------------------------------------------- tools

    private function seo(array $a): array
    {
        $rows = [];
        foreach ($this->pages($a) as $p) {
            $issues = $this->issues($p);
            $rows[] = ['path' => $p['path'], 'kind' => $p['kind'], 'score' => $this->score($issues), 'issues' => $issues];
        }
        usort($rows, fn ($x, $y) => $x['score'] <=> $y['score']);
        $limit = $this->int($a, 'limit', 50, false, 1, 200);

        return [
            'pages_checked' => count($rows),
            'average_score' => $rows ? round(array_sum(array_column($rows, 'score')) / count($rows)) : null,
            'pages' => array_slice($rows, 0, $limit),
        ];
    }

    private function meta(): array
    {
        $pages = array_filter($this->pages(), fn ($p) => $this->ok($p));
        $out = ['missing_title' => [], 'missing_description' => [], 'title_too_long' => [], 'title_too_short' => [], 'description_too_long' => [], 'description_too_short' => []];
        $titles = [];
        $descs = [];

        foreach ($pages as $p) {
            $t = trim((string) $p['title']);
            $d = trim((string) $p['description']);
            $tl = mb_strlen($t);
            $dl = mb_strlen($d);
            if ($tl === 0) {
                $out['missing_title'][] = $p['path'];
            } elseif ($tl > self::TITLE_MAX) {
                $out['title_too_long'][] = ['path' => $p['path'], 'length' => $tl, 'title' => $t];
            } elseif ($tl < self::TITLE_MIN) {
                $out['title_too_short'][] = ['path' => $p['path'], 'length' => $tl, 'title' => $t];
            }
            if ($dl === 0) {
                $out['missing_description'][] = $p['path'];
            } elseif ($dl > self::DESC_MAX) {
                $out['description_too_long'][] = ['path' => $p['path'], 'length' => $dl];
            } elseif ($dl < self::DESC_MIN) {
                $out['description_too_short'][] = ['path' => $p['path'], 'length' => $dl, 'description' => $d];
            }
            if ($t !== '') {
                $titles[mb_strtolower($t)][] = $p['path'];
            }
            if ($d !== '') {
                $descs[mb_strtolower($d)][] = $p['path'];
            }
        }

        $dupes = static fn (array $groups) => array_values(array_map(
            fn ($text, $paths) => ['text' => $text, 'paths' => $paths],
            array_keys($f = array_filter($groups, fn ($paths) => count($paths) > 1)),
            $f
        ));

        $out['duplicate_titles'] = $dupes($titles);
        $out['duplicate_descriptions'] = $dupes($descs);
        $out['pages_checked'] = count($pages);
        $out['limits'] = ['title' => [self::TITLE_MIN, self::TITLE_MAX], 'description' => [self::DESC_MIN, self::DESC_MAX]];

        return $out;
    }

    private function headings(array $a): array
    {
        $rows = [];
        foreach ($this->pages($a) as $p) {
            if (!$this->ok($p)) {
                continue;
            }
            $issues = $this->headingIssues($p);
            if ($issues || $this->str($a, 'url')) {
                $rows[] = [
                    'path' => $p['path'],
                    'kind' => $p['kind'],
                    'h1' => array_values(array_map(fn ($h) => $h['text'], array_filter($p['headings'], fn ($h) => $h['level'] === 1))),
                    'issues' => $issues,
                ] + ($this->str($a, 'url') ? ['headings' => $p['headings']] : []);
            }
        }

        return ['pages_with_issues' => count(array_filter($rows, fn ($r) => $r['issues'])), 'pages' => $rows];
    }

    private function images(array $a): array
    {
        $rows = [];
        foreach ($this->pages($a) as $p) {
            foreach ($p['images'] as $img) {
                $problems = [];
                if ($img['alt'] === null) {
                    $problems[] = 'missing alt';
                }
                if ($img['bytes'] !== null && $img['bytes'] > self::IMAGE_MAX_BYTES) {
                    $problems[] = 'large file (' . round($img['bytes'] / 1024) . ' KB)';
                }
                if ($img['format'] && !in_array($img['format'], ['webp', 'svg', 'avif'], true)) {
                    $problems[] = 'not WebP (' . $img['format'] . ')';
                }
                if (!$img['local'] && !preg_match('#^(https?:)?//#', $img['src'])) {
                    $problems[] = 'file not found';
                }
                if ($problems) {
                    $rows[] = ['path' => $p['path'], 'src' => $img['src'], 'alt' => $img['alt'], 'problems' => $problems];
                }
            }
        }

        return [
            'images_with_issues' => count($rows),
            'missing_alt' => count(array_filter($rows, fn ($r) => in_array('missing alt', $r['problems'], true))),
            'images' => array_slice($rows, 0, $this->int($a, 'limit', 100, false, 1, 200)),
        ];
    }

    private function performance(array $a): array
    {
        $rows = [];
        foreach ($this->pages($a) as $p) {
            if (!$this->ok($p)) {
                continue;
            }
            $local = array_filter($p['images'], fn ($i) => $i['bytes'] !== null);
            usort($local, fn ($x, $y) => $y['bytes'] <=> $x['bytes']);
            $imageBytes = array_sum(array_column($local, 'bytes'));
            $rows[] = [
                'path' => $p['path'],
                'html_kb' => round($p['html_bytes'] / 1024, 1),
                'images' => count($p['images']),
                'image_kb' => round($imageBytes / 1024),
                'total_kb' => round(($p['html_bytes'] + $imageBytes) / 1024),
                'largest_images' => array_map(fn ($i) => ['src' => $i['src'], 'kb' => round($i['bytes'] / 1024)], array_slice($local, 0, 3)),
                'render_blocking_scripts' => $p['blocking']['scripts'],
                'render_blocking_styles' => $p['blocking']['styles'],
            ];
        }
        usort($rows, fn ($x, $y) => $y['total_kb'] <=> $x['total_kb']);

        return [
            'note' => 'Sizes are the HTML plus local images in the page content; scripts, styles and fonts are listed, not weighed. For real-device timings use PageSpeed Insights.',
            'pages' => array_slice($rows, 0, $this->int($a, 'limit', 30, false, 1, 200)),
        ];
    }

    private function keywords(string $text): array
    {
        $text = mb_strtolower(preg_replace('/\s*[|–—-]\s*storage\s*keys.*$/iu', '', $text));
        preg_match_all('/[\p{L}\p{N}]+/u', $text, $m);

        return array_values(array_unique(array_diff($m[0], self::STOPWORDS)));
    }

    private function cannibalisation(array $a): array
    {
        $min = isset($a['min_overlap']) && is_numeric($a['min_overlap']) ? max(0.3, min(1, (float) $a['min_overlap'])) : 0.6;
        $pages = array_values(array_filter($this->pages(), fn ($p) => $this->ok($p) && $p['title']));
        $pairs = [];

        foreach ($pages as $i => $p) {
            $kp = $this->keywords((string) $p['title']);
            $h1p = mb_strtolower((string) (array_values(array_filter($p['headings'], fn ($h) => $h['level'] === 1))[0]['text'] ?? ''));
            for ($j = $i + 1; $j < count($pages); $j++) {
                $q = $pages[$j];
                $kq = $this->keywords((string) $q['title']);
                $union = array_unique(array_merge($kp, $kq));
                $overlap = $union ? count(array_intersect($kp, $kq)) / count($union) : 0;
                $h1q = mb_strtolower((string) (array_values(array_filter($q['headings'], fn ($h) => $h['level'] === 1))[0]['text'] ?? ''));
                $sameH1 = $h1p !== '' && $h1p === $h1q;
                if ($overlap >= $min || $sameH1) {
                    $pairs[] = [
                        'pages' => [$p['path'], $q['path']],
                        'titles' => [$p['title'], $q['title']],
                        'shared_keywords' => array_values(array_intersect($kp, $kq)),
                        'overlap' => round($overlap, 2),
                        'same_h1' => $sameH1,
                    ];
                }
            }
        }
        usort($pairs, fn ($x, $y) => [$y['same_h1'], $y['overlap']] <=> [$x['same_h1'], $x['overlap']]);

        return [
            'note' => 'City pages (e.g. /personal-storage and /personal-storage-dubai) overlap on purpose; make sure each targets its own city or angle in title, H1 and intro.',
            'min_overlap' => $min,
            'pairs' => $pairs,
        ];
    }

    private function thin(array $a): array
    {
        $min = $this->int($a, 'min_words', 600, false, 50);
        $rows = [];
        foreach ($this->pages() as $p) {
            if ($this->ok($p) && $p['words'] < $min) {
                $rows[] = ['path' => $p['path'], 'kind' => $p['kind'], 'words' => $p['words']];
            }
        }
        usort($rows, fn ($x, $y) => $x['words'] <=> $y['words']);

        return ['min_words' => $min, 'count' => count($rows), 'pages' => $rows, 'note' => 'Legal pages, the shop and booking pages are often short on purpose.'];
    }

    /** @return array<string, list<array{source: string, text: string}>> */
    private function inbound(): array
    {
        $map = [];
        foreach ($this->pages() as $p) {
            foreach ($p['links'] as $l) {
                $map[$l['target']][] = ['source' => $p['path'], 'text' => $l['text']];
            }
        }

        return $map;
    }

    private function anchors(array $a): array
    {
        $only = $this->str($a, 'target_url');
        $only = $only ? (SiteCrawler::internalPath($only) ?? '/' . trim($only, '/')) : null;
        $targets = [];
        $byText = [];
        $generic = [];

        foreach ($this->inbound() as $target => $links) {
            foreach ($links as $l) {
                $text = mb_strtolower(trim($l['text']));
                $byText[$text][$target] = true;
                if (in_array($text, self::GENERIC_ANCHORS, true) || $text === '') {
                    $generic[] = ['source' => $l['source'], 'target' => $target, 'text' => $l['text'] === '' ? '(no text, e.g. image link without alt)' : $l['text']];
                }
            }
            if ($only && $target !== $only) {
                continue;
            }
            $counts = array_count_values(array_map(fn ($l) => trim($l['text']) === '' ? '(no text)' : trim($l['text']), $links));
            arsort($counts);
            $targets[] = [
                'target' => $target,
                'inbound_links' => count($links),
                'linking_pages' => count(array_unique(array_column($links, 'source'))),
                'anchors' => $counts,
            ];
        }
        usort($targets, fn ($x, $y) => $y['inbound_links'] <=> $x['inbound_links']);

        $reused = [];
        foreach ($byText as $text => $ts) {
            if ($text !== '' && count($ts) > 1 && !in_array($text, self::GENERIC_ANCHORS, true)) {
                $reused[] = ['text' => $text, 'targets' => array_keys($ts)];
            }
        }

        return [
            'targets' => $targets,
            'generic_anchors' => $only ? array_values(array_filter($generic, fn ($g) => $g['target'] === $only)) : $generic,
            'same_anchor_different_targets' => $only ? [] : $reused,
            'note' => 'Counts links inside page content only (not the header and footer menus).',
        ];
    }

    private function orphans(): array
    {
        $inbound = $this->inbound();
        $rows = [];
        foreach ($this->pages() as $p) {
            if ($p['path'] === '/' || !$this->ok($p)) {
                continue;
            }
            $from = array_unique(array_column($inbound[$p['path']] ?? [], 'source'));
            if ($from === []) {
                $rows[] = ['path' => $p['path'], 'kind' => $p['kind']];
            }
        }

        return [
            'count' => count($rows),
            'pages' => $rows,
            'note' => 'No other page links here from its content. Header and footer menu links do not count. Add links with add_internal_link (DB pages and posts) or in the code (coded pages).',
        ];
    }

    private function report(): array
    {
        $pages = $this->pages();
        $seo = $this->seo(['limit' => 10]);
        $meta = $this->meta();
        $headingPages = $this->headings([])['pages_with_issues'];
        $images = $this->images(['limit' => 1]);
        $thin = $this->thin([]);
        $orphans = $this->orphans();
        $cannibal = $this->cannibalisation([]);

        $kinds = array_count_values(array_column($pages, 'kind'));

        return [
            'pages' => ['total' => count($pages), 'by_kind' => $kinds, 'not_200' => count(array_filter($pages, fn ($p) => !$this->ok($p)))],
            'average_seo_score' => $seo['average_score'],
            'worst_pages' => array_map(fn ($r) => ['path' => $r['path'], 'score' => $r['score']], $seo['pages']),
            'meta' => [
                'missing_titles' => count($meta['missing_title']),
                'missing_descriptions' => count($meta['missing_description']),
                'titles_too_long' => count($meta['title_too_long']),
                'titles_too_short' => count($meta['title_too_short']),
                'descriptions_too_long' => count($meta['description_too_long']),
                'descriptions_too_short' => count($meta['description_too_short']),
                'duplicate_titles' => count($meta['duplicate_titles']),
                'duplicate_descriptions' => count($meta['duplicate_descriptions']),
            ],
            'pages_with_heading_issues' => $headingPages,
            'images' => ['with_issues' => $images['images_with_issues'], 'missing_alt' => $images['missing_alt']],
            'thin_pages_under_600_words' => $thin['count'],
            'orphan_pages' => $orphans['count'],
            'competing_page_pairs' => count($cannibal['pairs']),
            'next_steps' => 'Run audit_seo, audit_meta, audit_headings, audit_images, audit_thin_content, find_orphan_pages or audit_cannibalisation for the details.',
        ];
    }
}
