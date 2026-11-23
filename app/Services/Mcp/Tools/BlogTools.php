<?php

namespace App\Services\Mcp\Tools;

use App\Models\Blog;
use App\Models\BlogCategory;
use App\Models\BlogTag;
use App\Services\Mcp\McpException;
use App\Services\Mcp\Revisions;
use App\Services\Mcp\Versioning;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

/**
 * Phase 5: blog publishing and scheduling, category and tag edits.
 */
class BlogTools extends Toolset
{
    private const TZ = 'Asia/Dubai';

    public function definitions(): array
    {
        $slug = ['type' => 'string', 'description' => 'Blog post slug or id'];
        $expected = ['type' => 'string', 'description' => 'updated_at from get_categories / get_tags'];

        return [
            $this->tool('publish_blog', 'Publish a blog post now (it appears on /blogs and in the sitemap). Clears any schedule. Only when the owner asks.', ['slug' => $slug], ['slug']),
            $this->tool('unpublish_blog', 'Take a blog post back to draft (hidden from visitors). Clears any schedule.', ['slug' => $slug], ['slug']),
            $this->tool('schedule_blog', 'Publish a draft blog post automatically at publish_at (Asia/Dubai time, e.g. "2026-12-01 09:00"). Pass an empty publish_at to cancel.', [
                'slug' => $slug,
                'publish_at' => ['type' => 'string', 'description' => 'Date and time in Asia/Dubai; empty to cancel the schedule'],
            ], ['slug', 'publish_at']),
            $this->tool('update_category', 'Rename a blog category or change its slug or description. Requires expected_updated_at.', [
                'id' => ['type' => 'integer'],
                'expected_updated_at' => $expected,
                'name' => ['type' => 'string'],
                'slug' => ['type' => 'string'],
                'description' => ['type' => 'string'],
            ], ['id', 'expected_updated_at']),
            $this->tool('delete_category', 'Move a blog category to the trash. move_posts_to (a category id) first adds that category to its posts.', [
                'id' => ['type' => 'integer'],
                'move_posts_to' => ['type' => 'integer'],
            ], ['id']),
            $this->tool('update_tag', 'Rename a blog tag or change its slug. Requires expected_updated_at.', [
                'id' => ['type' => 'integer'],
                'expected_updated_at' => $expected,
                'name' => ['type' => 'string'],
                'slug' => ['type' => 'string'],
            ], ['id', 'expected_updated_at']),
            $this->tool('delete_tag', 'Move a blog tag to the trash (restore_item type=tag brings it back with its posts).', [
                'id' => ['type' => 'integer'],
            ], ['id']),
            $this->tool('merge_tags', 'Merge tags into one: their posts get target_id, and the source tags go to the trash. The site has no public tag pages, so no redirects are needed.', [
                'source_ids' => ['type' => 'array', 'items' => ['type' => 'integer']],
                'target_id' => ['type' => 'integer'],
            ], ['source_ids', 'target_id']),
        ];
    }

    public function call(string $name, array $a): array
    {
        switch ($name) {
            case 'publish_blog':
                return $this->setStatus($a, 1);
            case 'unpublish_blog':
                return $this->setStatus($a, 0);
            case 'schedule_blog':
                return $this->schedule($a);
            case 'update_category':
                return $this->updateTerm($a, $this->category($a), 'category', ['name', 'slug', 'description']);
            case 'delete_category':
                return $this->deleteCategory($a);
            case 'update_tag':
                return $this->updateTerm($a, $this->tag($a), 'tag', ['name', 'slug']);
            case 'delete_tag':
                return $this->trash($this->tag($a), 'tag');
            case 'merge_tags':
                return $this->mergeTags($a);
        }

        throw new McpException('Unknown tool: ' . $name, 404);
    }

    private function blog(array $a): Blog
    {
        $key = (string) $this->str($a, 'slug', true);
        $q = Blog::query()->where('is_deleted', 0);
        $blog = is_numeric($key) ? $q->find((int) $key) : $q->where('slug', trim(preg_replace('#^/?blogs/#', '', $key), '/'))->first();
        if (!$blog) {
            throw McpException::notFound('Blog post "' . $key . '"');
        }

        return $blog;
    }

    private function present(Blog $blog): array
    {
        $publishAt = $blog->publish_at ? $blog->publish_at->copy()->setTimezone(self::TZ)->format('Y-m-d H:i') . ' (Asia/Dubai)' : null;

        return [
            'id' => $blog->id,
            'title' => $blog->title,
            'slug' => $blog->slug,
            'status' => (int) $blog->getRawOriginal('status') === 1 ? 'published' : 'draft',
            'publish_at' => $publishAt,
            'url' => url('/blogs/' . $blog->slug),
            'updated_at' => Versioning::stamp($blog),
        ];
    }

    private function setStatus(array $a, int $status): array
    {
        $blog = $this->blog($a);
        if ($status === 1 && (trim((string) $blog->title) === '' || trim(strip_tags((string) $blog->description)) === '')) {
            throw new McpException('This post has no title or body yet; it cannot be published.', 422);
        }
        $blog->status = $status;
        $blog->publish_at = null;
        $blog->save();
        $blog->refresh();

        return ['message' => $status ? 'Published.' : 'Moved back to draft.', 'blog' => $this->present($blog)];
    }

    private function schedule(array $a): array
    {
        $blog = $this->blog($a);
        $raw = $a['publish_at'] ?? null;

        if ($raw === null || $raw === '' || $raw === 'null') {
            $blog->publish_at = null;
            $blog->save();

            return ['message' => 'Schedule cancelled; the post stays a draft.', 'blog' => $this->present($blog->refresh())];
        }
        if ((int) $blog->getRawOriginal('status') === 1) {
            throw new McpException('This post is already published. Unpublish it first to schedule it.', 422);
        }
        try {
            $at = Carbon::parse((string) $raw, self::TZ);
        } catch (\Throwable $e) {
            throw new McpException('publish_at is not a valid date and time (Asia/Dubai), e.g. "2026-12-01 09:00".', 422);
        }
        if ($at->lessThanOrEqualTo(Carbon::now(self::TZ))) {
            throw new McpException('publish_at must be in the future (Asia/Dubai time now: ' . Carbon::now(self::TZ)->format('Y-m-d H:i') . ').', 422);
        }

        $blog->publish_at = $at->copy()->setTimezone(config('app.timezone'));
        $blog->save();

        return [
            'message' => 'Scheduled. The post goes live at ' . $at->format('Y-m-d H:i') . ' Asia/Dubai (checked every minute by the server scheduler).',
            'blog' => $this->present($blog->refresh()),
        ];
    }

    // ---------------------------------------------------------------- taxonomy

    private function category(array $a, string $key = 'id'): BlogCategory
    {
        $c = BlogCategory::query()->where('is_deleted', 0)->find($this->int($a, $key, null, true));
        if (!$c) {
            throw McpException::notFound('Category ' . ($a[$key] ?? ''));
        }

        return $c;
    }

    private function tag(array $a, ?int $id = null): BlogTag
    {
        $id ??= $this->int($a, 'id', null, true);
        $t = BlogTag::query()->where('is_deleted', 0)->find($id);
        if (!$t) {
            throw McpException::notFound('Tag ' . $id);
        }

        return $t;
    }

    private function updateTerm(array $a, Model $term, string $type, array $fields): array
    {
        Versioning::assertCurrent($term, $a['expected_updated_at'] ?? null, ucfirst($type));
        foreach ($fields as $field) {
            if (!array_key_exists($field, $a) || $a[$field] === null) {
                continue;
            }
            $value = trim(strip_tags((string) $a[$field]));
            if ($field === 'name' && ($value === '' || mb_strlen($value) > 120)) {
                throw new McpException('name must be 1-120 characters.', 422);
            }
            if ($field === 'slug') {
                $value = Str::slug($value);
                if ($value === '') {
                    throw new McpException('slug is empty after cleaning.', 422);
                }
                if ($term->newQuery()->where('slug', $value)->whereKeyNot($term->getKey())->exists()) {
                    throw new McpException('Another ' . $type . ' (maybe in the trash) already uses the slug "' . $value . '".', 422);
                }
            }
            $term->setAttribute($field, $value);
        }
        if (!$term->isDirty()) {
            throw new McpException('Nothing to change.', 422);
        }
        $term->save();

        return ['message' => ucfirst($type) . ' updated.', $type => $term->only(['id', 'name', 'slug', 'description']) + ['updated_at' => Versioning::stamp($term)]];
    }

    private function trash(Model $term, string $type): array
    {
        $term->setAttribute('is_deleted', 1);
        $term->save();

        return ['message' => ucfirst($type) . ' moved to the trash. restore_item with type=' . $type . ' and id=' . $term->getKey() . ' brings it back.', 'id' => $term->getKey()];
    }

    private function deleteCategory(array $a): array
    {
        $category = $this->category($a);
        $moved = 0;
        if (isset($a['move_posts_to'])) {
            $target = $this->category($a, 'move_posts_to');
            if ($target->id === $category->id) {
                throw new McpException('move_posts_to must be a different category.', 422);
            }
            DB::transaction(function () use ($category, $target, &$moved) {
                $ids = $category->blogs()->pluck('blogs.id')->all();
                $moved = count($target->blogs()->syncWithoutDetaching($ids)['attached']);
                Revisions::record($category, 'moved', null, null, count($ids) . ' post(s) given category #' . $target->id . ' (' . $target->name . ')');
            });
        }

        return $this->trash($category, 'category') + ['posts_added_to_target' => $moved];
    }

    private function mergeTags(array $a): array
    {
        $target = $this->tag($a, $this->int($a, 'target_id', null, true));
        $sources = array_values(array_unique(array_map('intval', $this->arr($a, 'source_ids', true))));
        if ($sources === [] || in_array($target->id, $sources, true)) {
            throw new McpException('source_ids must list other tags than target_id.', 422);
        }
        $tags = array_map(fn ($id) => $this->tag($a, $id), $sources);

        $moved = 0;
        DB::transaction(function () use ($tags, $target, &$moved) {
            foreach ($tags as $tag) {
                $ids = $tag->blogs()->pluck('blogs.id')->all();
                $moved += count($target->blogs()->syncWithoutDetaching($ids)['attached']);
                $tag->blogs()->detach();
                Revisions::record($tag, 'merged', null, null, 'Merged into tag #' . $target->id . ' (' . $target->name . '); ' . count($ids) . ' post(s) moved');
                $tag->is_deleted = 1;
                $tag->save();
            }
        });

        return [
            'message' => 'Merged ' . count($tags) . ' tag(s) into "' . $target->name . '". The old tags are in the trash.',
            'target' => $target->only(['id', 'name', 'slug']),
            'posts_newly_tagged' => $moved,
            'note' => 'The site has no public tag pages, so no redirects were needed.',
        ];
    }
}
