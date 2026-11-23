<?php

namespace App\Services\Mcp\Tools;

use App\Models\Blog;
use App\Models\Page;
use App\Services\Mcp\Html;
use App\Services\Mcp\McpException;
use App\Services\Mcp\PageFetcher;
use App\Services\Mcp\RobotsTxt;
use App\Services\Mcp\SchemaRules;
use App\Services\SitemapBuilder;
use App\Support\LocationPages;

/**
 * Phase 7: JSON-LD for pages and posts, and robots.txt.
 * Saved blocks go to pages.schema_json / blogs.schema_json; coded pages get generated JSON only.
 */
class SchemaTools extends Toolset
{
    public const TYPES = ['SelfStorage', 'LocalBusiness', 'Service', 'BreadcrumbList', 'FAQPage'];

    public function definitions(): array
    {
        $slug = ['type' => 'string', 'description' => 'Page slug/path or blog post path (/blogs/x)'];

        return [
            $this->tool('generate_schema', 'Build a JSON-LD block from the page itself and the real facility details: SelfStorage or LocalBusiness (needs city), Service, BreadcrumbList or FAQPage (from the page\'s FAQ questions). save=true stores it on a DB page or blog post (replacing its block of the same type); coded pages only get the JSON. A type the page template already outputs is refused.', [
                'slug' => $slug,
                'type' => ['type' => 'string', 'enum' => self::TYPES],
                'city' => ['type' => 'string', 'enum' => LocationPages::CITIES, 'description' => 'For SelfStorage/LocalBusiness and Service area; guessed from the slug when omitted'],
                'save' => ['type' => 'boolean'],
            ], ['slug', 'type']),
            $this->tool('delete_schema', 'Remove the saved JSON-LD block of one @type from a DB page or blog post.', [
                'slug' => $slug,
                'type' => ['type' => 'string'],
            ], ['slug', 'type']),
            $this->tool('update_robots', 'Replace robots.txt. Checked first: it must have a User-agent, list ' . 'the sitemap URL, and must not block the whole site. The previous text stays in activity_log (entity_type robots).', [
                'content' => ['type' => 'string'],
            ], ['content']),
            $this->tool('get_robots', 'The robots.txt the site serves now, with validation results.'),
        ];
    }

    public function call(string $name, array $a): array
    {
        switch ($name) {
            case 'generate_schema':
                return $this->generate($a);
            case 'delete_schema':
                return $this->delete($a);
            case 'update_robots':
                return $this->updateRobots($a);
            case 'get_robots':
                $content = RobotsTxt::content();

                return ['url' => url('/robots.txt'), 'content' => $content] + RobotsTxt::validate($content);
        }

        throw new McpException('Unknown tool: ' . $name, 404);
    }

    // ---------------------------------------------------------------- target

    /** @return array{kind: string, model: ?object, path: string, title: string, description: ?string, h1: ?string, html: string, main: string} */
    private function target(string $slug): array
    {
        $path = PageFetcher::path($slug);
        $model = null;
        $kind = 'coded_page';
        if (str_starts_with($path, '/blogs/')) {
            $model = Blog::query()->where('is_deleted', 0)->where('slug', substr($path, 7))->first();
            $kind = $model ? 'blog' : $kind;
        } else {
            $model = Page::query()->active()->where('slug', trim($path, '/'))->first();
            $kind = $model ? 'db_page' : $kind;
        }

        $fetched = PageFetcher::fetch($path);
        if ($fetched['status'] !== 200 && !$model) {
            throw new McpException('Page ' . $path . ' returned HTTP ' . $fetched['status'] . '.', 404);
        }
        $meta = Html::meta($fetched['html']);
        $h1 = null;
        foreach (Html::headings($fetched['main']) as $h) {
            if ($h['level'] === 1) {
                $h1 = $h['text'];
                break;
            }
        }
        $title = $h1 ?: preg_replace('/\s*[|–-]\s*Storage\s*Keys.*$/i', '', (string) $meta['title']);

        return [
            'kind' => $kind,
            'model' => $model,
            'path' => $path,
            'title' => trim((string) ($model->title ?? '') ?: $title),
            'h1' => $h1,
            'description' => $meta['description'],
            'html' => $fetched['html'],
            'main' => $fetched['main'],
        ];
    }

    private function base(): string
    {
        return app(SitemapBuilder::class)->baseUrl();
    }

    private function city(array $a, string $path): ?string
    {
        $city = $this->str($a, 'city');
        if ($city) {
            if (!in_array($city, LocationPages::CITIES, true)) {
                throw new McpException('city must be one of: ' . implode(', ', LocationPages::CITIES) . '.', 422);
            }

            return $city;
        }
        foreach (LocationPages::CITIES as $c) {
            if (str_contains($path, $c)) {
                return $c;
            }
        }

        return null;
    }

    // ---------------------------------------------------------------- builders

    private function build(string $type, array $t, ?string $city): array
    {
        $base = $this->base();
        $url = $base . ($t['path'] === '/' ? '/' : $t['path']);

        switch ($type) {
            case 'SelfStorage':
            case 'LocalBusiness':
                if (!$city) {
                    throw new McpException('Pass city (' . implode(' or ', LocationPages::CITIES) . ') for a ' . $type . ' block.', 422);
                }
                $f = LocationPages::facilities()[$city];
                $node = [
                    '@type' => $type,
                    '@id' => $base . '/#storagekeys-' . $city,
                    'name' => 'StorageKeys ' . $f['city'],
                    'url' => $base . '/' . LocationPages::cityPageSlug($city),
                    'telephone' => '+971 56 501 8785',
                    'email' => 'sales@storagekeys.com',
                    'image' => $base . '/sk-assets/assets/images/frontend/front-logo.png',
                    'address' => array_merge(['@type' => 'PostalAddress', 'addressCountry' => 'AE'], $f['schemaAddress']),
                    'hasMap' => $f['directions'],
                    'openingHours' => 'Mo-Su 00:00-23:59',
                    'areaServed' => array_map(fn ($c) => ['@type' => 'City', 'name' => $c], $f['schemaAreas']),
                    'parentOrganization' => ['@id' => $base . '/#organization'],
                ];
                if (!empty($f['geo'])) {
                    $node['geo'] = ['@type' => 'GeoCoordinates', 'latitude' => $f['geo'][0], 'longitude' => $f['geo'][1]];
                }

                return $node;

            case 'Service':
                $areas = $city ? [LocationPages::facilities()[$city]['city']] : array_map(fn ($c) => LocationPages::facilities()[$c]['city'], LocationPages::CITIES);

                return array_filter([
                    '@type' => 'Service',
                    'name' => $t['title'],
                    'serviceType' => $t['title'],
                    'provider' => ['@id' => $base . '/#organization'],
                    'areaServed' => array_map(fn ($c) => ['@type' => 'City', 'name' => $c], $areas),
                    'url' => $url,
                    'description' => $t['description'],
                ]);

            case 'BreadcrumbList':
                $items = [['Home', $base . '/']];
                if ($t['kind'] === 'blog') {
                    $items[] = ['Blog', $base . '/blogs'];
                } elseif ($city && trim($t['path'], '/') !== LocationPages::cityPageSlug($city) && in_array(trim($t['path'], '/'), LocationPages::slugs(), true)) {
                    $items[] = ['Self Storage in ' . LocationPages::facilities()[$city]['city'], $base . '/' . LocationPages::cityPageSlug($city)];
                }
                if ($t['path'] !== '/') {
                    $items[] = [$t['title'], $url];
                }

                return [
                    '@type' => 'BreadcrumbList',
                    'itemListElement' => array_map(fn ($item, $i) => ['@type' => 'ListItem', 'position' => $i + 1, 'name' => $item[0], 'item' => $item[1]], $items, array_keys($items)),
                ];

            case 'FAQPage':
                $faqs = $this->faqsFromPage($t);
                if ($faqs === []) {
                    throw new McpException('No FAQ questions found on this page (<details><summary>Question?</summary> or headings ending in "?"). Add FAQs first.', 422);
                }

                return self::faqNode($faqs);
        }

        throw new McpException('type must be one of: ' . implode(', ', self::TYPES) . '.', 422);
    }

    /** @param list<array{0: string, 1: string}> $faqs */
    public static function faqNode(array $faqs): array
    {
        return [
            '@type' => 'FAQPage',
            'mainEntity' => array_map(fn ($f) => [
                '@type' => 'Question',
                'name' => $f[0],
                'acceptedAnswer' => ['@type' => 'Answer', 'text' => $f[1]],
            ], $faqs),
        ];
    }

    /** Questions and answers in the page body: details/summary, or headings ending in "?". */
    private function faqsFromPage(array $t): array
    {
        $xp = Html::dom($t['main']);
        $faqs = [];
        foreach ($xp->query('//details[summary]') as $d) {
            $q = Html::text($xp->query('./summary', $d)->item(0));
            $answer = '';
            foreach ($d->childNodes as $child) {
                if ($child->nodeName !== 'summary') {
                    $answer .= ' ' . $child->textContent;
                }
            }
            $answer = trim(preg_replace('/\s+/u', ' ', $answer));
            if ($q !== '' && $answer !== '') {
                $faqs[$q] = [$q, $answer];
            }
        }
        foreach ($xp->query('//h2|//h3|//h4|//h5') as $h) {
            $q = Html::text($h);
            if (!str_ends_with($q, '?') || isset($faqs[$q]) || $h->parentNode->nodeName === 'summary') {
                continue;
            }
            $answer = '';
            for ($n = $h->nextSibling; $n; $n = $n->nextSibling) {
                if (preg_match('/^h[1-6]$/', $n->nodeName)) {
                    break;
                }
                $answer .= ' ' . $n->textContent;
            }
            $answer = trim(preg_replace('/\s+/u', ' ', $answer));
            if ($answer !== '') {
                $faqs[$q] = [$q, $answer];
            }
        }

        return array_values($faqs);
    }

    // ---------------------------------------------------------------- save / delete

    /** @return list<array> */
    private function stored(?object $model): array
    {
        return $model ? SchemaRules::nodes($model->schemaArray()) : [];
    }

    /** Types the page outputs that are not from its saved schema (layout, template partials). */
    private function templateTypes(array $t): array
    {
        $saved = array_count_values(array_map([SchemaRules::class, 'typeOf'], $this->stored($t['model'])));
        $types = [];
        foreach (Html::schemaNodes($t['html']) as $node) {
            $type = SchemaRules::typeOf($node);
            if (!empty($saved[$type])) {
                $saved[$type]--;
                continue;
            }
            $types[$type] = true;
        }

        return array_keys($types);
    }

    private function writeStored(object $model, array $nodes): void
    {
        $model->schema_json = $nodes === [] ? null : json_encode(['@context' => 'https://schema.org', '@graph' => array_values($nodes)], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        $model->save();
    }

    private function generate(array $a): array
    {
        $type = (string) $this->str($a, 'type', true);
        if (!in_array($type, self::TYPES, true)) {
            throw new McpException('type must be one of: ' . implode(', ', self::TYPES) . '. Review and AggregateRating are not offered: StorageKeys may not mark up its own reviews.', 422);
        }
        $t = $this->target((string) $this->str($a, 'slug', true));
        $node = $this->build($type, $t, $this->city($a, $t['path']));
        $result = ['type' => $type, 'path' => $t['path'], 'kind' => $t['kind'], 'schema' => ['@context' => 'https://schema.org'] + $node];

        $template = $this->templateTypes($t);
        if (in_array($type, $template, true)) {
            $result['warning'] = 'The page template already outputs a ' . $type . ' block, so this one is not needed (a page has one block per type).';
        }

        if (!$this->bool($a, 'save')) {
            return $result + ['saved' => false, 'note' => $t['kind'] === 'coded_page' ? 'Coded page: add this JSON-LD in the code.' : 'Preview only; pass save=true to store it.'];
        }
        if (!$t['model']) {
            throw new McpException('Coded (Blade) pages cannot store schema here; add the JSON-LD in the code.', 422, ['schema' => $result['schema']]);
        }
        if (isset($result['warning'])) {
            throw new McpException($result['warning'], 422);
        }

        $nodes = array_values(array_filter($this->stored($t['model']), fn ($n) => SchemaRules::typeOf($n) !== $type));
        $nodes[] = $node;
        if ($problems = SchemaRules::problems($nodes)) {
            throw new McpException('Schema refused.', 422, ['problems' => $problems]);
        }
        $this->writeStored($t['model'], $nodes);

        return $result + ['saved' => true, 'stored_types' => array_map([SchemaRules::class, 'typeOf'], $nodes)];
    }

    private function delete(array $a): array
    {
        $type = (string) $this->str($a, 'type', true);
        $t = $this->target((string) $this->str($a, 'slug', true));
        if (!$t['model']) {
            throw new McpException('Coded (Blade) page: its schema is in the code.', 422);
        }
        $nodes = $this->stored($t['model']);
        $kept = array_values(array_filter($nodes, fn ($n) => SchemaRules::typeOf($n) !== $type));
        if (count($kept) === count($nodes)) {
            $msg = in_array($type, $this->templateTypes($t), true)
                ? 'The ' . $type . ' block comes from the page template (code), not from saved schema.'
                : 'No saved ' . $type . ' block on this page.';
            throw new McpException($msg, 404);
        }
        $this->writeStored($t['model'], $kept);

        return ['message' => $type . ' block removed.', 'stored_types' => array_map([SchemaRules::class, 'typeOf'], $kept)];
    }

    private function updateRobots(array $a): array
    {
        $content = (string) $this->str($a, 'content', true);
        if (strlen($content) > 50000) {
            throw new McpException('robots.txt is too long.', 422);
        }
        $check = RobotsTxt::validate($content);
        if ($check['errors']) {
            throw new McpException('robots.txt not saved.', 422, $check);
        }
        RobotsTxt::save($content);

        return ['message' => 'robots.txt updated.', 'url' => url('/robots.txt'), 'warnings' => $check['warnings']];
    }
}
