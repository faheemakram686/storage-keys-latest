<?php

namespace App\Services\Mcp;

use App\Models\Blog;
use App\Models\Page;
use App\Models\PageSection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

/**
 * Editable HTML on the site: html sections of DB pages and blog post bodies.
 * Link, text, image and heading tools work through this, so a page edit always lands
 * in the right section and the page is rebuilt.
 */
class ContentTargets
{
    /**
     * @return list<array{kind: string, model: Model, field: string, owner: string, path: string, label: string}>
     */
    public static function all(string $scope = 'all'): array
    {
        $docs = [];
        if (in_array($scope, ['all', 'page'], true)) {
            $pages = Page::query()->active()->orderBy('id')->get();
            foreach ($pages as $page) {
                $docs = array_merge($docs, self::forPage($page));
            }
        }
        if (in_array($scope, ['all', 'blog'], true)) {
            foreach (Blog::query()->where('is_deleted', 0)->orderBy('id')->get() as $blog) {
                $docs[] = self::blogDoc($blog);
            }
        }

        return $docs;
    }

    /** @return list<array> */
    public static function forPage(Page $page): array
    {
        $docs = [];
        foreach (Sections::forPage($page) as $s) {
            if ($s->type === 'html') {
                $docs[] = [
                    'kind' => 'section',
                    'model' => $s,
                    'field' => 'html',
                    'owner' => $page->slug,
                    'path' => '/' . $page->slug,
                    'label' => 'page ' . $page->slug . ', section ' . $s->id . ' (' . $s->name . ')',
                ];
            }
        }

        return $docs;
    }

    public static function blogDoc(Blog $blog): array
    {
        return [
            'kind' => 'blog',
            'model' => $blog,
            'field' => 'description',
            'owner' => $blog->slug,
            'path' => '/blogs/' . $blog->slug,
            'label' => 'blog ' . $blog->slug,
        ];
    }

    /**
     * The editable parts of one DB page or blog post, by slug or path ("self-storage-dubai", "/blogs/x").
     *
     * @return array{type: string, model: Model, docs: list<array>}
     */
    public static function forSlug(string $slugOrPath): array
    {
        $path = PageFetcher::path($slugOrPath);
        if (str_starts_with($path, '/blogs/')) {
            $blog = Blog::query()->where('is_deleted', 0)->where('slug', substr($path, 7))->first();
            if ($blog) {
                return ['type' => 'blog', 'model' => $blog, 'docs' => [self::blogDoc($blog)]];
            }
        }
        $slug = trim($path, '/');
        $page = Page::query()->active()->where('slug', $slug)->first();
        if ($page) {
            return ['type' => 'page', 'model' => $page, 'docs' => self::forPage($page)];
        }
        $blog = Blog::query()->where('is_deleted', 0)->where('slug', $slug)->first();
        if ($blog) {
            return ['type' => 'blog', 'model' => $blog, 'docs' => [self::blogDoc($blog)]];
        }

        throw new McpException(
            '"' . $slugOrPath . '" is not a DB page or blog post. Coded (Blade) pages can only be changed in the code.',
            404
        );
    }

    public static function html(array $doc): string
    {
        return (string) $doc['model']->getAttribute($doc['field']);
    }

    /** Save new HTML into a doc; rebuilds the page for sections. */
    public static function save(array $doc, string $html): void
    {
        DB::transaction(function () use ($doc, $html) {
            $model = $doc['model'];
            $model->setAttribute($doc['field'], $html);
            if (!$model->isDirty()) {
                return;
            }
            $model->save();
            if ($model instanceof PageSection && $model->page) {
                Sections::rebuild($model->page->fresh());
            }
        });
    }

    /** Public description of a doc for tool results. */
    public static function describe(array $doc): array
    {
        return array_filter([
            'where' => $doc['label'],
            'path' => $doc['path'],
            'section_id' => $doc['kind'] === 'section' ? $doc['model']->id : null,
            'blog_id' => $doc['kind'] === 'blog' ? $doc['model']->id : null,
        ], fn ($v) => $v !== null);
    }
}
