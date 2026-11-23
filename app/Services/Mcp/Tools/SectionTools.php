<?php

namespace App\Services\Mcp\Tools;

use App\Http\Controllers\Api\McpPageController;
use App\Models\ContentRevision;
use App\Models\Page;
use App\Models\PageSection;
use App\Services\Mcp\Components;
use App\Services\Mcp\HtmlSanitizer;
use App\Services\Mcp\Html;
use App\Services\Mcp\McpException;
use App\Services\Mcp\PageFetcher;
use App\Services\Mcp\Revisions;
use App\Services\Mcp\Sections;
use App\Services\Mcp\Versioning;
use Illuminate\Support\Facades\DB;

/**
 * Phase 2: sections of DB (CMS) pages, their revisions, outline and components.
 */
class SectionTools extends Toolset
{
    public function definitions(): array
    {
        $page = ['type' => 'string', 'description' => 'Slug of a DB page (see list_pages)'];
        $section = ['type' => 'integer', 'description' => 'Section id from get_page_sections'];
        $expected = ['type' => 'string', 'description' => 'The section\'s updated_at from your last read'];

        return [
            $this->tool('get_page_sections', 'Ordered sections of a DB page: id, type (html|component), name, html or component+attrs, visible, position, updated_at. The page renders its visible sections in order. Coded (Blade) pages have no sections; read them with get_page_outline.', [
                'page_slug' => $page,
            ], ['page_slug']),
            $this->tool('add_page_section', 'Add a section to a DB page. type=html needs content (HTML, sanitized; do not add an <h1> when the page already has one); type=component needs component (see list_components) and attrs. position is 1-based; default is the end.', [
                'page_slug' => $page,
                'type' => ['type' => 'string', 'enum' => ['html', 'component']],
                'content' => ['type' => 'string', 'description' => 'HTML for type=html'],
                'component' => ['type' => 'string'],
                'attrs' => ['type' => 'object'],
                'name' => ['type' => 'string', 'description' => 'Short label; defaults to the first heading'],
                'position' => ['type' => 'integer', 'minimum' => 1],
            ], ['page_slug', 'type']),
            $this->tool('update_page_section', 'Edit one section. Small edits: find + replace (find must match exactly once, or pass occurrence). Full rewrite: content. Component sections: attrs (merged). Requires expected_updated_at.', [
                'section_id' => $section,
                'expected_updated_at' => $expected,
                'content' => ['type' => 'string', 'description' => 'New full HTML (html sections)'],
                'find' => ['type' => 'string'],
                'replace' => ['type' => 'string'],
                'occurrence' => ['type' => 'integer', 'minimum' => 1, 'description' => 'Which match of find to replace when it appears more than once'],
                'attrs' => ['type' => 'object', 'description' => 'Component attributes to change'],
                'name' => ['type' => 'string'],
            ], ['section_id', 'expected_updated_at']),
            $this->tool('delete_page_section', 'Move a section to the trash (restore_item type=section brings it back). Requires expected_updated_at.', [
                'section_id' => $section,
                'expected_updated_at' => $expected,
            ], ['section_id', 'expected_updated_at']),
            $this->tool('reorder_page_sections', 'Set the order of a page\'s sections. section_ids must list every active section of the page exactly once, in the new order.', [
                'page_slug' => $page,
                'section_ids' => ['type' => 'array', 'items' => ['type' => 'integer']],
            ], ['page_slug', 'section_ids']),
            $this->tool('toggle_section_visibility', 'Show or hide a section without deleting it.', [
                'section_id' => $section,
                'visible' => ['type' => 'boolean'],
                'expected_updated_at' => $expected,
            ], ['section_id', 'visible']),
            $this->tool('list_section_revisions', 'Revision history of one section: revision id, date, source/tool and a summary of what changed.', [
                'section_id' => $section,
                'limit' => ['type' => 'integer', 'minimum' => 1, 'maximum' => 100],
            ], ['section_id']),
            $this->tool('restore_section_revision', 'Put a section back to how it was right after a given revision (its content, attrs, name and visibility). The restore is itself a new revision.', [
                'section_id' => $section,
                'revision_id' => ['type' => 'integer'],
            ], ['section_id', 'revision_id']),
            $this->tool('get_page_outline', 'Heading tree (H1-H3) and word count of any page: DB pages with section ids, coded pages and blog posts by rendering them.', [
                'slug' => ['type' => 'string', 'description' => 'Page slug or path, e.g. self-storage-dubai, /blogs/my-post, /'],
            ], ['slug']),
            $this->tool('list_components', 'Components a section can hold (type=component) and their attributes.'),
        ];
    }

    public function call(string $name, array $a): array
    {
        switch ($name) {
            case 'get_page_sections':
                return $this->getSections($this->page($a));
            case 'add_page_section':
                return $this->add($a);
            case 'update_page_section':
                return $this->update($a);
            case 'delete_page_section':
                return $this->delete($a);
            case 'reorder_page_sections':
                return $this->reorder($a);
            case 'toggle_section_visibility':
                return $this->toggle($a);
            case 'list_section_revisions':
                return $this->revisions($a);
            case 'restore_section_revision':
                return $this->restoreRevision($a);
            case 'get_page_outline':
                return $this->outline($a);
            case 'list_components':
                return $this->components();
        }

        throw new McpException('Unknown tool: ' . $name, 404);
    }

    // ---------------------------------------------------------------

    private function page(array $a): Page
    {
        $slug = trim((string) $this->str($a, 'page_slug', true), '/');
        $page = Page::query()->active()->where('slug', $slug)->first();
        if (!$page) {
            throw new McpException('No DB page with slug "' . $slug . '". Coded (Blade) pages have no sections: read them with get_page_outline and change them in the code.', 404);
        }

        return $page;
    }

    private function section(array $a): PageSection
    {
        $section = PageSection::query()->active()->find($this->int($a, 'section_id', null, true));
        if (!$section) {
            throw McpException::notFound('Section ' . ($a['section_id'] ?? ''));
        }

        return $section;
    }

    private function presentSection(PageSection $s): array
    {
        $out = [
            'id' => $s->id,
            'position' => (int) $s->position,
            'type' => $s->type,
            'name' => $s->name,
            'visible' => (bool) $s->is_visible,
            'updated_at' => Versioning::stamp($s),
        ];
        if ($s->type === 'component') {
            $out['component'] = $s->component;
            $out['attrs'] = (object) ($s->attrs ?? []);
        } else {
            $out['content'] = $s->html;
            $out['words'] = Html::wordCount((string) $s->html);
        }

        return $out;
    }

    private function pageInfo(Page $page): array
    {
        $info = app(McpPageController::class)->formatPage($page->fresh(), false);

        return array_intersect_key($info, array_flip(['id', 'title', 'slug', 'status_label', 'url', 'live', 'live_note', 'banner_hidden', 'updated_at']));
    }

    private function getSections(Page $page, array $extra = []): array
    {
        $page = $page->fresh(); // after a write the caller's copy holds the old content
        $warnings = [];
        $sections = Sections::forPage($page, $warnings);

        return array_merge([
            'page' => $this->pageInfo($page),
            'count' => $sections->count(),
            'sections' => $sections->map(fn (PageSection $s) => $this->presentSection($s))->values(),
        ], $warnings ? ['warnings' => $warnings] : [], $extra);
    }

    /** @return array{0: string, 1: list<string>} */
    private function cleanHtml(string $html): array
    {
        $clean = HtmlSanitizer::clean($html);
        \App\Services\Mcp\Placeholders::assertKnown($clean['html']);

        return [$clean['html'], $clean['removed']];
    }

    private function assertOneH1(Page $page, string $newContent): array
    {
        return Sections::assertOneH1($page, $newContent);
    }

    private function previewContent(Page $page, ?PageSection $changed, ?PageSection $added = null): string
    {
        $sections = Sections::active($page)->map(fn ($s) => $changed && $s->id === $changed->id ? $changed : $s);
        if ($added) {
            $sections->push($added);
        }

        return Sections::build($sections);
    }

    private function add(array $a): array
    {
        $page = $this->page($a);
        Sections::forPage($page);
        $type = $this->str($a, 'type', true);
        $removed = [];

        if ($type === 'html') {
            [$html, $removed] = $this->cleanHtml((string) $this->str($a, 'content', true));
            $attrs = ['type' => 'html', 'html' => $html];
        } elseif ($type === 'component') {
            $component = (string) $this->str($a, 'component', true);
            $attrs = ['type' => 'component', 'component' => $component, 'attrs' => Components::validateAttrs($component, $this->arr($a, 'attrs'))];
        } else {
            throw new McpException('type must be html or component.', 422);
        }

        $position = $this->int($a, 'position');
        $attrs['name'] = $this->str($a, 'name') ?: Sections::guessName($attrs['html'] ?? null, $attrs['component'] ?? null, $position ?? Sections::active($page)->count() + 1);

        $warnings = $this->assertOneH1($page, $this->previewContent($page, null, new PageSection($attrs + ['is_visible' => true])));

        $section = DB::transaction(function () use ($page, $attrs, $position) {
            $section = Sections::insertAt($page, $attrs, $position);
            Sections::rebuild($page->fresh());

            return $section;
        });

        return array_merge(
            ['message' => 'Section added.', 'section' => $this->presentSection($section->fresh())],
            $removed ? ['sanitized_removed' => $removed] : [],
            $warnings ? ['warnings' => $warnings] : []
        );
    }

    private function update(array $a): array
    {
        $section = $this->section($a);
        Versioning::assertCurrent($section, $a['expected_updated_at'] ?? null, 'Section');
        $page = $section->page;
        $removed = [];

        if ($section->type === 'component') {
            if (array_key_exists('content', $a) || array_key_exists('find', $a)) {
                throw new McpException('This is a component section: change it with attrs, or delete it and add an html section.', 422);
            }
            if (array_key_exists('attrs', $a)) {
                $section->attrs = Components::validateAttrs((string) $section->component, array_merge((array) ($section->attrs ?? []), $this->arr($a, 'attrs')));
            }
        } else {
            if (array_key_exists('find', $a)) {
                $html = $this->findReplace((string) $section->html, (string) $this->str($a, 'find', true), (string) ($a['replace'] ?? ''), $this->int($a, 'occurrence'));
            } elseif (array_key_exists('content', $a)) {
                $html = (string) $this->str($a, 'content', true);
            } else {
                $html = null;
            }
            if ($html !== null) {
                [$html, $removed] = $this->cleanHtml($html);
                $section->html = $html;
            }
        }
        if ($name = $this->str($a, 'name')) {
            $section->name = mb_substr($name, 0, 120);
        }
        if (!$section->isDirty()) {
            throw new McpException('Nothing to change: pass content, find+replace, attrs or name.', 422);
        }

        $warnings = $this->assertOneH1($page, $this->previewContent($page, $section));
        DB::transaction(function () use ($section, $page) {
            $section->save();
            Sections::rebuild($page->fresh());
        });

        return array_merge(
            ['message' => 'Section updated.', 'section' => $this->presentSection($section->fresh())],
            $removed ? ['sanitized_removed' => $removed] : [],
            $warnings ? ['warnings' => $warnings] : []
        );
    }

    private function findReplace(string $html, string $find, string $replace, ?int $occurrence): string
    {
        $count = substr_count($html, $find);
        if ($count === 0) {
            throw new McpException('find text not found in this section. It must match the stored HTML exactly (including tags and spacing).', 422);
        }
        if ($count > 1 && $occurrence === null) {
            throw new McpException('find text appears ' . $count . ' times. Add more surrounding text, or pass occurrence (1-' . $count . ').', 422, ['matches' => $count]);
        }
        $n = $occurrence ?? 1;
        if ($n > $count) {
            throw new McpException('occurrence ' . $n . ' does not exist; find appears ' . $count . ' times.', 422);
        }
        $offset = -1;
        for ($i = 0; $i < $n; $i++) {
            $offset = strpos($html, $find, $offset + 1);
        }

        return substr_replace($html, $replace, $offset, strlen($find));
    }

    private function delete(array $a): array
    {
        $section = $this->section($a);
        Versioning::assertCurrent($section, $a['expected_updated_at'] ?? null, 'Section');
        $page = $section->page;

        DB::transaction(function () use ($section, $page) {
            $section->is_deleted = 1;
            $section->save();
            Sections::renumber($page);
            Sections::rebuild($page->fresh());
        });

        return ['message' => 'Section moved to the trash. restore_item with type=section and id=' . $section->id . ' brings it back.', 'section_id' => $section->id];
    }

    private function reorder(array $a): array
    {
        $page = $this->page($a);
        $current = Sections::forPage($page)->pluck('id')->map(fn ($id) => (int) $id)->all();
        $wanted = array_map('intval', $this->arr($a, 'section_ids', true));

        $sortedCurrent = $current;
        $sortedWanted = $wanted;
        sort($sortedCurrent);
        sort($sortedWanted);
        if ($sortedCurrent !== $sortedWanted) {
            throw new McpException('section_ids must list every active section of this page exactly once.', 422, ['current_order' => $current]);
        }

        DB::transaction(function () use ($page, $wanted) {
            Sections::renumber($page, $wanted);
            Sections::rebuild($page->fresh());
        });

        return $this->getSections($page, ['message' => 'Sections reordered.']);
    }

    private function toggle(array $a): array
    {
        $section = $this->section($a);
        if (array_key_exists('expected_updated_at', $a)) {
            Versioning::assertCurrent($section, $a['expected_updated_at'], 'Section');
        }
        if (!array_key_exists('visible', $a)) {
            throw new McpException('visible is required.', 422);
        }
        $section->is_visible = $this->bool($a, 'visible');
        if (!$section->isDirty()) {
            return ['message' => 'Section was already ' . ($section->is_visible ? 'visible' : 'hidden') . '.', 'section' => $this->presentSection($section)];
        }

        $page = $section->page;
        $warnings = $section->is_visible ? $this->assertOneH1($page, $this->previewContent($page, $section)) : [];
        DB::transaction(function () use ($section, $page) {
            $section->save();
            Sections::rebuild($page->fresh());
        });

        return array_merge(
            ['message' => $section->is_visible ? 'Section shown.' : 'Section hidden.', 'section' => $this->presentSection($section->fresh())],
            $warnings ? ['warnings' => $warnings] : []
        );
    }

    private function revisions(array $a): array
    {
        $id = $this->int($a, 'section_id', null, true);
        if (!PageSection::query()->find($id)) {
            throw McpException::notFound('Section ' . $id);
        }
        $rows = ContentRevision::query()->where('entity_type', 'section')->where('entity_id', $id)
            ->orderByDesc('id')->limit($this->int($a, 'limit', 30, false, 1, 100))->get();

        return [
            'section_id' => $id,
            'count' => $rows->count(),
            'revisions' => $rows->map(fn (ContentRevision $r) => Revisions::present($r, false, 200))->values(),
        ];
    }

    private function restoreRevision(array $a): array
    {
        $id = $this->int($a, 'section_id', null, true);
        $section = PageSection::query()->find($id);
        if (!$section) {
            throw McpException::notFound('Section ' . $id);
        }
        $rev = ContentRevision::query()->where('entity_type', 'section')->where('entity_id', $id)->find($this->int($a, 'revision_id', null, true));
        if (!$rev) {
            throw new McpException('Revision not found for this section.', 404);
        }
        if (!is_array($rev->after)) {
            throw new McpException('That revision has no content to restore (it records a permanent delete).', 422);
        }

        foreach (['type', 'name', 'component', 'html', 'is_visible'] as $field) {
            if (array_key_exists($field, $rev->after)) {
                $section->setAttribute($field, $rev->after[$field]);
            }
        }
        $section->attrs = isset($rev->after['attrs']) ? json_decode((string) $rev->after['attrs'], true) : null;
        $wasTrashed = (int) $section->is_deleted === 1;
        $section->is_deleted = 0;

        $page = $section->page;
        if ($section->isDirty()) {
            $this->assertOneH1($page, $this->previewContent($page, $section, $wasTrashed ? $section : null));
            DB::transaction(function () use ($section, $page) {
                $section->save();
                Sections::renumber($page);
                Sections::rebuild($page->fresh());
            });
        }

        return ['message' => 'Section restored to revision ' . $rev->id . '.', 'section' => $this->presentSection($section->fresh())];
    }

    private function outline(array $a): array
    {
        $slug = trim((string) $this->str($a, 'slug', true));
        $dbSlug = trim(PageFetcher::path($slug), '/');
        $page = Page::query()->active()->where('slug', $dbSlug)->first();

        if ($page) {
            $sections = Sections::forPage($page);
            $tree = [];
            if (!$page->shouldHideBanner()) {
                $tree[] = ['level' => 1, 'text' => $page->title, 'section_id' => null, 'source' => 'template banner (page title)'];
            }
            foreach ($sections as $s) {
                if (!$s->is_visible) {
                    continue;
                }
                foreach (Html::headings(Sections::markup($s)) as $h) {
                    if ($h['level'] <= 3) {
                        $tree[] = $h + ['section_id' => $s->id];
                    }
                }
            }

            return array_merge([
                'kind' => 'db_page',
                'page' => $this->pageInfo($page),
                'words' => Html::wordCount((string) $page->content),
            ], $this->summarizeTree($tree));
        }

        $fetched = PageFetcher::fetch($slug);
        if ($fetched['status'] !== 200) {
            throw new McpException('Page ' . $fetched['path'] . ' returned HTTP ' . $fetched['status'] . '.', 404);
        }
        $tree = array_values(array_filter(Html::headings($fetched['main']), fn ($h) => $h['level'] <= 3));

        return array_merge([
            'kind' => str_starts_with($fetched['path'], '/blogs/') ? 'blog' : 'coded_page',
            'path' => $fetched['path'],
            'note' => str_starts_with($fetched['path'], '/blogs/') ? null : 'Coded (Blade) page: read-only here, change it in the code.',
            'words' => Html::wordCount($fetched['main']),
        ], $this->summarizeTree($tree));
    }

    private function summarizeTree(array $tree): array
    {
        $issues = [];
        $h1 = count(array_filter($tree, fn ($h) => $h['level'] === 1));
        if ($h1 !== 1) {
            $issues[] = 'Page has ' . $h1 . ' H1 headings (should be exactly 1).';
        }
        $prev = 0;
        foreach ($tree as $h) {
            if ($h['text'] === '') {
                $issues[] = 'Empty H' . $h['level'] . ' heading.';
            }
            if ($prev && $h['level'] > $prev + 1) {
                $issues[] = 'Skipped level: H' . $prev . ' followed by H' . $h['level'] . ' ("' . $h['text'] . '").';
            }
            $prev = $h['level'];
        }

        return ['headings' => $tree, 'issues' => $issues];
    }

    private function components(): array
    {
        $out = [];
        foreach (Components::definitions() as $name => $def) {
            $attrs = [];
            foreach ($def['attrs'] as $key => $spec) {
                unset($spec['var']);
                $attrs[$key] = $spec;
            }
            $out[] = ['name' => $name, 'description' => $def['description'], 'attrs' => $attrs];
        }

        return ['count' => count($out), 'components' => $out];
    }
}
