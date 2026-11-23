<?php

namespace App\Services\Mcp\Tools;

use App\Models\Blog;
use App\Models\Page;
use App\Models\PageSection;
use App\Services\Mcp\ContentTargets;
use App\Services\Mcp\HtmlEdit;
use App\Services\Mcp\McpException;
use App\Services\Mcp\PageFetcher;
use App\Services\Mcp\Sections;
use App\Services\Mcp\SiteCrawler;
use Illuminate\Support\Facades\DB;

/**
 * Phase 4: internal links, sitewide text/link/image replacement, headings and bulk SEO/alt edits.
 * Works on DB content (page sections and blog posts). Sitewide and bulk tools preview until dry_run=false.
 */
class LinkTools extends Toolset
{
    public function definitions(): array
    {
        $source = ['type' => 'string', 'description' => 'DB page slug or blog post slug/path (e.g. self-storage-dubai, /blogs/my-post)'];
        $dry = ['type' => 'boolean', 'description' => 'Default true: only preview. Pass false to apply.'];
        $scope = ['type' => 'string', 'enum' => ['page', 'blog', 'all']];

        return [
            $this->tool('add_internal_link', 'Turn existing words on a DB page or blog post into a link. anchor_text must already be in the text (not inside another link). occurrence picks which match (default 1).', [
                'source_slug' => $source,
                'anchor_text' => ['type' => 'string'],
                'target_url' => ['type' => 'string', 'description' => 'Path like /personal-storage or a full URL'],
                'occurrence' => ['type' => 'integer', 'minimum' => 1],
            ], ['source_slug', 'anchor_text', 'target_url']),
            $this->tool('update_internal_link', 'Point links on one page/post from old_target to new_target; optionally change the link text.', [
                'source_slug' => $source,
                'old_target' => ['type' => 'string'],
                'new_target' => ['type' => 'string'],
                'new_anchor' => ['type' => 'string'],
            ], ['source_slug', 'old_target', 'new_target']),
            $this->tool('remove_internal_link', 'Remove links to target_url from one page/post. keep_text (default true) leaves the words in place.', [
                'source_slug' => $source,
                'target_url' => ['type' => 'string'],
                'keep_text' => ['type' => 'boolean'],
            ], ['source_slug', 'target_url']),
            $this->tool('replace_text', 'Find and replace visible text (not tags or attributes) across DB pages and/or blog posts. Case-sensitive. Previews matches until dry_run=false. Coded pages are not changed.', [
                'find' => ['type' => 'string'],
                'replace' => ['type' => 'string', 'description' => 'Plain text (HTML is escaped)'],
                'scope' => $scope,
                'dry_run' => $dry,
            ], ['find', 'replace']),
            $this->tool('replace_link_sitewide', 'Change every link to old_url into new_url across DB pages and blog posts. Previews until dry_run=false. For a moved page, also add a redirect with create_redirect.', [
                'old_url' => ['type' => 'string'],
                'new_url' => ['type' => 'string'],
                'scope' => $scope,
                'dry_run' => $dry,
            ], ['old_url', 'new_url']),
            $this->tool('replace_image', 'Swap an image in DB pages and blog posts (img tags and blog featured images); optionally set new alt text. Previews until dry_run=false.', [
                'old_path' => ['type' => 'string'],
                'new_path' => ['type' => 'string'],
                'new_alt' => ['type' => 'string'],
                'dry_run' => $dry,
            ], ['old_path', 'new_path']),
            $this->tool('update_heading', 'Change the text and/or level of one heading on a DB page or blog post, found by its current text. Keeps exactly one H1.', [
                'slug' => $source,
                'old_text' => ['type' => 'string'],
                'new_text' => ['type' => 'string'],
                'new_level' => ['type' => 'integer', 'minimum' => 1, 'maximum' => 6],
            ], ['slug', 'old_text']),
            $this->tool('bulk_update_seo', 'Set meta title/description (and canonical/robots for blog posts) on many DB pages and posts at once. Shows before/after until dry_run=false. Coded pages are refused per item.', [
                'items' => ['type' => 'array', 'items' => ['type' => 'object', 'properties' => [
                    'slug' => ['type' => 'string'],
                    'meta_title' => ['type' => 'string'],
                    'meta_description' => ['type' => 'string'],
                    'canonical' => ['type' => 'string'],
                    'robots' => ['type' => 'string'],
                ], 'required' => ['slug']]],
                'dry_run' => $dry,
            ], ['items']),
            $this->tool('bulk_update_alt', 'Set alt text for images by path across DB pages and blog posts. Shows matches until dry_run=false.', [
                'items' => ['type' => 'array', 'items' => ['type' => 'object', 'properties' => [
                    'image_path' => ['type' => 'string'],
                    'alt' => ['type' => 'string'],
                ], 'required' => ['image_path', 'alt']]],
                'dry_run' => $dry,
            ], ['items']),
        ];
    }

    public function call(string $name, array $a): array
    {
        switch ($name) {
            case 'add_internal_link':
                return $this->addLink($a);
            case 'update_internal_link':
                return $this->updateLink($a);
            case 'remove_internal_link':
                return $this->removeLink($a);
            case 'replace_text':
                return $this->replaceText($a);
            case 'replace_link_sitewide':
                return $this->replaceLinkSitewide($a);
            case 'replace_image':
                return $this->replaceImage($a);
            case 'update_heading':
                return $this->updateHeading($a);
            case 'bulk_update_seo':
                return $this->bulkSeo($a);
            case 'bulk_update_alt':
                return $this->bulkAlt($a);
        }

        throw new McpException('Unknown tool: ' . $name, 404);
    }

    private function dryRun(array $a): bool
    {
        return $this->bool($a, 'dry_run', true);
    }

    private function scope(array $a): string
    {
        $scope = $this->str($a, 'scope') ?: 'all';
        if (!in_array($scope, ['page', 'blog', 'all'], true)) {
            throw new McpException('scope must be page, blog or all.', 422);
        }

        return $scope;
    }

    private static function e(string $text): string
    {
        return htmlspecialchars($text, ENT_QUOTES | ENT_HTML5, 'UTF-8');
    }

    /** Link targets: internal paths stay relative, other URLs must be http(s). */
    private function target(string $url): string
    {
        $url = trim($url);
        $internal = SiteCrawler::internalPath($url);
        if ($internal !== null) {
            $fragment = parse_url($url, PHP_URL_FRAGMENT);

            return $internal . ($fragment ? '#' . $fragment : '');
        }
        if (!preg_match('#^https?://#i', $url)) {
            throw new McpException('Target must be a path like /personal-storage or an http(s) URL.', 422);
        }

        return $url;
    }

    private function warnIfMissing(string $target): array
    {
        $path = SiteCrawler::internalPath($target);
        if ($path === null) {
            return [];
        }
        try {
            $status = PageFetcher::fetch($path)['status'];
        } catch (\Throwable $e) {
            return [];
        }

        return $status === 200 ? [] : ['Target ' . $path . ' returns HTTP ' . $status . '.'];
    }

    // ---------------------------------------------------------------- per page/post

    private function addLink(array $a): array
    {
        $found = ContentTargets::forSlug((string) $this->str($a, 'source_slug', true));
        $anchor = (string) $this->str($a, 'anchor_text', true);
        $href = $this->target((string) $this->str($a, 'target_url', true));
        $n = $this->int($a, 'occurrence', 1, false, 1);

        $seen = 0;
        foreach ($found['docs'] as $doc) {
            $html = ContentTargets::html($doc);
            $here = HtmlEdit::countText($html, $anchor, true);
            if ($seen + $here >= $n) {
                [$new, $done] = HtmlEdit::replaceText($html, $anchor, '<a href="' . self::e($href) . '">' . self::e($anchor) . '</a>', $n - $seen, true);
                if ($done !== 1) {
                    break;
                }
                ContentTargets::save($doc, $new);

                return array_merge([
                    'message' => 'Linked "' . $anchor . '" to ' . $href . '.',
                    'occurrence' => $n,
                ], ContentTargets::describe($doc), ($w = $this->warnIfMissing($href)) ? ['warnings' => $w] : []);
            }
            $seen += $here;
        }

        throw new McpException(
            $seen === 0
                ? '"' . $anchor . '" was not found as plain text (outside existing links) on this page.'
                : 'Only ' . $seen . ' match(es) of "' . $anchor . '" outside links; occurrence ' . $n . ' does not exist.',
            422,
            ['matches' => $seen]
        );
    }

    private function updateLink(array $a): array
    {
        $found = ContentTargets::forSlug((string) $this->str($a, 'source_slug', true));
        $old = (string) $this->str($a, 'old_target', true);
        $new = $this->target((string) $this->str($a, 'new_target', true));
        $anchor = $this->str($a, 'new_anchor');

        $total = 0;
        $changed = [];
        DB::transaction(function () use ($found, $old, $new, $anchor, &$total, &$changed) {
            foreach ($found['docs'] as $doc) {
                [$html, $count] = HtmlEdit::mapLinks(ContentTargets::html($doc), $old, function (string $open, string $inner) use ($new, $anchor) {
                    return HtmlEdit::setAttr($open, 'href', $new) . ($anchor !== null && $anchor !== '' ? self::e($anchor) : $inner) . '</a>';
                });
                if ($count) {
                    ContentTargets::save($doc, $html);
                    $total += $count;
                    $changed[] = ContentTargets::describe($doc);
                }
            }
        });
        if ($total === 0) {
            throw new McpException('No link to ' . $old . ' on this page.', 404);
        }

        return array_merge(['message' => 'Updated ' . $total . ' link(s).', 'links_updated' => $total, 'changed' => $changed], ($w = $this->warnIfMissing($new)) ? ['warnings' => $w] : []);
    }

    private function removeLink(array $a): array
    {
        $found = ContentTargets::forSlug((string) $this->str($a, 'source_slug', true));
        $target = (string) $this->str($a, 'target_url', true);
        $keep = $this->bool($a, 'keep_text', true);

        $total = 0;
        DB::transaction(function () use ($found, $target, $keep, &$total) {
            foreach ($found['docs'] as $doc) {
                [$html, $count] = HtmlEdit::mapLinks(ContentTargets::html($doc), $target, fn ($open, $inner) => $keep ? $inner : '');
                if ($count) {
                    ContentTargets::save($doc, $html);
                    $total += $count;
                }
            }
        });
        if ($total === 0) {
            throw new McpException('No link to ' . $target . ' on this page.', 404);
        }

        return ['message' => 'Removed ' . $total . ' link(s)' . ($keep ? ', text kept.' : ' and their text.'), 'links_removed' => $total];
    }

    private function updateHeading(array $a): array
    {
        $found = ContentTargets::forSlug((string) $this->str($a, 'slug', true));
        $oldText = (string) $this->str($a, 'old_text', true);
        $newText = $this->str($a, 'new_text');
        $newLevel = $this->int($a, 'new_level', null, false, 1, 6);
        if (($newText === null || $newText === '') && $newLevel === null) {
            throw new McpException('Pass new_text, new_level or both.', 422);
        }

        $hits = [];
        foreach ($found['docs'] as $doc) {
            [$html, $count] = HtmlEdit::mapHeadings(ContentTargets::html($doc), $oldText, function (int $level, string $attrs, string $inner) use ($newText, $newLevel) {
                $lv = $newLevel ?? $level;

                return '<h' . $lv . $attrs . '>' . ($newText !== null && $newText !== '' ? self::e($newText) : $inner) . '</h' . $lv . '>';
            });
            if ($count) {
                $hits[] = [$doc, $html, $count];
            }
        }
        $total = array_sum(array_column($hits, 2));
        if ($total === 0) {
            throw new McpException('No heading with the text "' . $oldText . '" on this page. Use get_page_outline to see the headings.', 404);
        }
        if ($total > 1) {
            throw new McpException($total . ' headings have this text; this tool changes one. Edit the section with update_page_section instead.', 422);
        }

        [$doc, $html] = $hits[0];
        $warnings = $this->checkH1($found, $doc, $html);
        ContentTargets::save($doc, $html);

        return array_merge(['message' => 'Heading updated.'], ContentTargets::describe($doc), $warnings ? ['warnings' => $warnings] : []);
    }

    /** Exactly one H1 on the page after the change. */
    private function checkH1(array $found, array $doc, string $newHtml): array
    {
        if ($found['type'] === 'page') {
            /** @var Page $page */
            $page = $found['model'];
            $sections = Sections::active($page)->map(function (PageSection $s) use ($doc, $newHtml) {
                if ($s->id === $doc['model']->id) {
                    $s = clone $s;
                    $s->html = $newHtml;
                }

                return $s;
            });

            return Sections::assertOneH1($page, Sections::build($sections));
        }

        // Blog posts: the template shows the post title as the H1, so the body must not add another.
        $h1 = preg_match_all('#<h1\b#i', $newHtml);
        if ($h1 > preg_match_all('#<h1\b#i', ContentTargets::html($doc))) {
            throw new McpException('A blog post already has its title as the H1; use H2 in the body.', 422);
        }

        return [];
    }

    // ---------------------------------------------------------------- sitewide

    private function replaceText(array $a): array
    {
        $find = (string) $this->str($a, 'find', true);
        $replace = (string) ($a['replace'] ?? '');
        $dry = $this->dryRun($a);
        $matches = [];
        $total = 0;

        DB::transaction(function () use ($find, $replace, $dry, $a, &$matches, &$total) {
            foreach (ContentTargets::all($this->scope($a)) as $doc) {
                $html = ContentTargets::html($doc);
                $count = HtmlEdit::countText($html, $find);
                if (!$count) {
                    continue;
                }
                $total += $count;
                $matches[] = ContentTargets::describe($doc) + ['matches' => $count, 'preview' => HtmlEdit::snippets($html, $find)];
                if (!$dry) {
                    [$new] = HtmlEdit::replaceText($html, $find, self::e($replace));
                    ContentTargets::save($doc, $new);
                }
            }
        });

        return $this->bulkResult($dry, $total, $matches, 'match(es) of "' . $find . '"');
    }

    private function replaceLinkSitewide(array $a): array
    {
        $old = (string) $this->str($a, 'old_url', true);
        $new = $this->target((string) $this->str($a, 'new_url', true));
        $dry = $this->dryRun($a);
        $matches = [];
        $total = 0;

        DB::transaction(function () use ($old, $new, $dry, $a, &$matches, &$total) {
            foreach (ContentTargets::all($this->scope($a)) as $doc) {
                [$html, $count] = HtmlEdit::mapLinks(ContentTargets::html($doc), $old, fn ($open, $inner) => HtmlEdit::setAttr($open, 'href', $new) . $inner . '</a>');
                if (!$count) {
                    continue;
                }
                $total += $count;
                $matches[] = ContentTargets::describe($doc) + ['links' => $count];
                if (!$dry) {
                    ContentTargets::save($doc, $html);
                }
            }
        });

        $result = $this->bulkResult($dry, $total, $matches, 'link(s) to ' . $old);
        if ($w = $this->warnIfMissing($new)) {
            $result['warnings'] = $w;
        }

        return $result;
    }

    private function replaceImage(array $a): array
    {
        $old = (string) $this->str($a, 'old_path', true);
        $new = (string) $this->str($a, 'new_path', true);
        $alt = $this->str($a, 'new_alt');
        $dry = $this->dryRun($a);
        $matches = [];
        $total = 0;

        DB::transaction(function () use ($old, $new, $alt, $dry, &$matches, &$total) {
            foreach (ContentTargets::all() as $doc) {
                [$html, $count] = HtmlEdit::mapImages(ContentTargets::html($doc), $old, function (string $tag) use ($new, $alt) {
                    $tag = preg_replace('#\s(data-src|srcset)\s*=\s*(["\']).*?\2#is', '', $tag);
                    $tag = HtmlEdit::setAttr($tag, 'src', $new);

                    return $alt !== null ? HtmlEdit::setAttr($tag, 'alt', $alt) : $tag;
                });
                if ($count) {
                    $total += $count;
                    $matches[] = ContentTargets::describe($doc) + ['images' => $count];
                    if (!$dry) {
                        ContentTargets::save($doc, $html);
                    }
                }
            }

            // Blog featured images are stored as a file name under storage/uploads/blog-images.
            $oldName = basename((string) parse_url($old, PHP_URL_PATH));
            if (str_contains($old, 'blog-images/') && $oldName !== '') {
                foreach (Blog::query()->where('is_deleted', 0)->where('image', $oldName)->get() as $blog) {
                    $total++;
                    $matches[] = ['where' => 'blog ' . $blog->slug . ' (featured image)', 'blog_id' => $blog->id, 'images' => 1];
                    if (!$dry) {
                        if (!str_contains($new, 'blog-images/')) {
                            throw new McpException('A blog featured image must stay in storage/uploads/blog-images; upload it there first.', 422);
                        }
                        $blog->image = basename((string) parse_url($new, PHP_URL_PATH));
                        $blog->save();
                    }
                }
            }
        });

        return $this->bulkResult($dry, $total, $matches, 'use(s) of ' . $old);
    }

    private function bulkSeo(array $a): array
    {
        $items = $this->arr($a, 'items', true);
        $dry = $this->dryRun($a);
        $rows = [];
        $errors = [];

        $plan = [];
        foreach ($items as $i => $item) {
            if (!is_array($item) || empty($item['slug'])) {
                $errors[] = ['item' => $i, 'error' => 'slug is required.'];
                continue;
            }
            try {
                $found = ContentTargets::forSlug((string) $item['slug']);
            } catch (McpException $e) {
                $errors[] = ['slug' => $item['slug'], 'error' => $e->getMessage()];
                continue;
            }
            $model = $found['model'];
            $fields = ['meta_title' => 'meta_title', 'meta_description' => 'meta_description'];
            if ($found['type'] === 'blog') {
                $fields += ['canonical' => 'canonical_url', 'robots' => 'robots'];
            }
            $changes = [];
            foreach ($item as $key => $value) {
                if ($key === 'slug') {
                    continue;
                }
                if (!isset($fields[$key])) {
                    $errors[] = ['slug' => $item['slug'], 'error' => $key . ' cannot be set on a ' . $found['type'] . ($found['type'] === 'page' ? ' (pages have meta_title and meta_description)' : '') . '.'];
                    continue;
                }
                $value = trim(strip_tags((string) $value));
                if ($key === 'meta_title' && mb_strlen($value) > 255 || $key === 'meta_description' && mb_strlen($value) > 512) {
                    $errors[] = ['slug' => $item['slug'], 'error' => $key . ' is too long.'];
                    continue;
                }
                if ($key === 'canonical' && $value !== '' && !preg_match('#^https://#i', $value)) {
                    $errors[] = ['slug' => $item['slug'], 'error' => 'canonical must be an https:// URL.'];
                    continue;
                }
                $col = $fields[$key];
                if ((string) $model->getAttribute($col) !== $value) {
                    $changes[$col] = ['before' => $model->getAttribute($col), 'after' => $value === '' ? null : $value];
                }
            }
            if ($changes) {
                $plan[] = [$model, $changes];
                $rows[] = ['slug' => $item['slug'], 'type' => $found['type'], 'changes' => $changes];
            }
        }

        if (!$dry && $errors === []) {
            DB::transaction(function () use ($plan) {
                foreach ($plan as [$model, $changes]) {
                    foreach ($changes as $col => $c) {
                        $model->setAttribute($col, $c['after']);
                    }
                    $model->save();
                }
            });
        }

        return [
            'dry_run' => $dry,
            'applied' => !$dry && $errors === [],
            'message' => $errors && !$dry ? 'Nothing was changed: fix the errors first (all items are applied together).' : ($dry ? 'Preview only. Call again with dry_run=false to apply.' : 'Applied ' . count($rows) . ' change(s).'),
            'items' => $rows,
            'errors' => $errors,
        ];
    }

    private function bulkAlt(array $a): array
    {
        $items = $this->arr($a, 'items', true);
        $dry = $this->dryRun($a);
        $rows = [];
        $total = 0;

        DB::transaction(function () use ($items, $dry, &$rows, &$total) {
            $docs = ContentTargets::all();
            foreach ($items as $item) {
                if (!is_array($item) || empty($item['image_path']) || !array_key_exists('alt', $item)) {
                    throw new McpException('Each item needs image_path and alt.', 422);
                }
                $alt = trim(strip_tags((string) $item['alt']));
                $count = 0;
                foreach ($docs as $doc) {
                    [$html, $n] = HtmlEdit::mapImages(ContentTargets::html($doc), (string) $item['image_path'], fn ($tag) => HtmlEdit::setAttr($tag, 'alt', $alt));
                    if ($n) {
                        $count += $n;
                        if (!$dry) {
                            ContentTargets::save($doc, $html);
                        }
                    }
                }
                $total += $count;
                $rows[] = ['image_path' => $item['image_path'], 'alt' => $alt, 'images_found' => $count];
            }
        });

        $result = $this->bulkResult($dry, $total, $rows, 'image tag(s)');
        $result['note'] = 'Only images inside DB pages and blog posts are changed; images on coded pages need a code change.';

        return $result;
    }

    private function bulkResult(bool $dry, int $total, array $items, string $what): array
    {
        return [
            'dry_run' => $dry,
            'total' => $total,
            'message' => $dry
                ? ($total ? 'Preview: ' . $total . ' ' . $what . '. Call again with dry_run=false to apply.' : 'No ' . $what . ' found.')
                : 'Changed ' . $total . ' ' . $what . '.',
            'items' => $items,
        ];
    }
}
