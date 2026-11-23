<?php

namespace App\Services\Mcp;

use App\Models\Page;
use App\Models\PageSection;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

/**
 * Keeps a DB page's sections and its pages.content in step. The site keeps rendering pages.content;
 * after every section change it is rebuilt from the visible sections, joined by a newline
 * (one section = its HTML byte for byte, so migrated pages render exactly as before).
 */
class Sections
{
    /** Active sections in order. Creates the first section, or re-imports content changed elsewhere. */
    public static function forPage(Page $page, ?array &$warnings = null): Collection
    {
        $warnings ??= [];
        $sections = self::active($page);

        if ($sections->isEmpty()) {
            if (trim((string) $page->content) !== '') {
                self::create($page, ['type' => 'html', 'name' => 'Main content', 'html' => $page->content, 'position' => 1]);
                $sections = self::active($page);
            }

            return $sections;
        }

        // Compare with the stored content, never a stale in-memory copy.
        $stored = (string) Page::query()->whereKey($page->id)->value('content');
        if (self::build($sections) !== $stored) {
            $page->content = $stored;
            // pages.content was changed without the section tools (old update_page call, direct DB edit).
            self::replaceWithContent($page, (string) $page->content);
            $warnings[] = 'The page content had been changed outside the section tools, so its sections were rebuilt from it as one html section. Earlier sections are in the trash.';
            $sections = self::active($page);
        }

        return $sections;
    }

    public static function active(Page $page): Collection
    {
        return PageSection::query()->where('page_id', $page->id)->active()->orderBy('position')->orderBy('id')->get();
    }

    public static function markup(PageSection $s): string
    {
        return $s->type === 'component'
            ? Components::marker((string) $s->component, (array) ($s->attrs ?? []))
            : (string) $s->html;
    }

    public static function build(Collection $sections): string
    {
        return $sections->filter(fn (PageSection $s) => $s->is_visible)->map(fn (PageSection $s) => self::markup($s))->implode("\n");
    }

    /** Write the rebuilt content to the page (one page revision when it changed). */
    public static function rebuild(Page $page): void
    {
        $content = self::build(self::active($page));
        if ($content !== (string) $page->content) {
            $page->content = $content;
            $page->save();
        }
        SiteCache::touch();
    }

    public static function create(Page $page, array $attrs): PageSection
    {
        return PageSection::create(array_merge(['page_id' => $page->id, 'is_visible' => true, 'is_deleted' => false], $attrs));
    }

    /** Positions 1..n in the current order. */
    public static function renumber(Page $page, ?array $orderedIds = null): void
    {
        $sections = self::active($page);
        if ($orderedIds !== null) {
            $sections = $sections->sortBy(fn (PageSection $s) => array_search($s->id, $orderedIds, true))->values();
        }
        foreach ($sections->values() as $i => $s) {
            if ((int) $s->position !== $i + 1) {
                $s->position = $i + 1;
                $s->save();
            }
        }
    }

    /** Insert at a 1-based position (end when null), shifting the rest down. */
    public static function insertAt(Page $page, array $attrs, ?int $position): PageSection
    {
        return DB::transaction(function () use ($page, $attrs, $position) {
            $sections = self::active($page)->values();
            $position = $position === null ? $sections->count() + 1 : max(1, min($position, $sections->count() + 1));
            foreach ($sections as $i => $s) {
                $want = $i + 1 >= $position ? $i + 2 : $i + 1;
                if ((int) $s->position !== $want) {
                    $s->position = $want;
                    $s->save();
                }
            }

            return self::create($page, array_merge($attrs, ['position' => $position]));
        });
    }

    /** The old update_page tool sets the whole content: keep sections in step with it. */
    public static function replaceWithContent(Page $page, string $content): void
    {
        DB::transaction(function () use ($page, $content) {
            $sections = self::active($page);
            if ($sections->count() === 1 && $sections[0]->type === 'html') {
                $sections[0]->html = $content;
                $sections[0]->is_visible = true;
                $sections[0]->save();

                return;
            }
            foreach ($sections as $s) {
                $s->is_deleted = 1;
                $s->save();
            }
            if (trim($content) !== '') {
                self::create($page, ['type' => 'html', 'name' => 'Main content', 'html' => $content, 'position' => 1]);
            }
        });
    }

    /** A short label for a section: its first heading, else its component, else "Section n". */
    public static function guessName(?string $html, ?string $component, int $position): string
    {
        if ($component) {
            return 'Component: ' . $component;
        }
        foreach (Html::headings((string) $html) as $h) {
            if ($h['text'] !== '') {
                return mb_substr($h['text'], 0, 120);
            }
        }

        return 'Section ' . $position;
    }

    /**
     * Exactly one H1 per page: refuse a change that adds a second one.
     */
    public static function assertOneH1(Page $page, string $newContent): array
    {
        $count = static function (string $content) use ($page): int {
            $probe = new Page(['content' => $content, 'hide_banner' => $page->hide_banner]);
            $h1s = count(array_filter(Html::headings($content), fn ($h) => $h['level'] === 1));

            return $h1s + ($probe->shouldHideBanner() ? 0 : 1);
        };

        $after = $count($newContent);
        $before = $count((string) $page->content);
        if ($after > 1 && $after > $before) {
            throw new McpException(
                'This would give the page ' . $after . ' H1 headings; a page has exactly one. '
                . ($page->shouldHideBanner() ? 'Use <h2> for this heading.' : 'The page title is already the H1 (from the template banner); use <h2>.'),
                422
            );
        }

        return $after === 0 ? ['The page has no H1. Add one (with hide_banner pages the content must contain it).'] : [];
    }
}
