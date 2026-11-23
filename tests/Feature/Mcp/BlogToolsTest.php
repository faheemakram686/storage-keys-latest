<?php

namespace Tests\Feature\Mcp;

use App\Models\Blog;
use App\Models\BlogCategory;
use App\Models\BlogTag;
use Illuminate\Support\Carbon;

class BlogToolsTest extends McpTestCase
{
    private function blog(array $attrs = []): Blog
    {
        return Blog::create(array_merge([
            'title' => 'MCP Blog Tools Post',
            'slug' => 'mcp-blog-tools-' . uniqid(),
            'description' => '<p>Body of the post.</p>',
            'image' => 'empty',
            'status' => 0,
            'is_deleted' => 0,
        ], $attrs));
    }

    public function test_publish_and_unpublish(): void
    {
        $blog = $this->blog();
        $this->get('/blogs/' . $blog->slug)->assertStatus(404);

        $this->assertSame('published', $this->ok('publish_blog', ['slug' => $blog->slug])['blog']['status']);
        $this->assertSame(1, (int) $blog->fresh()->getRawOriginal('status'));

        $this->assertSame('draft', $this->ok('unpublish_blog', ['slug' => '/blogs/' . $blog->slug])['blog']['status']);
        $this->assertSame(0, (int) $blog->fresh()->getRawOriginal('status'));
    }

    public function test_empty_post_cannot_be_published(): void
    {
        $blog = $this->blog(['description' => '<p> </p>']);
        $this->fails('publish_blog', ['slug' => $blog->slug]);
    }

    public function test_schedule_in_dubai_time_and_publish_by_command(): void
    {
        $blog = $this->blog();
        $at = Carbon::now('Asia/Dubai')->addHour()->format('Y-m-d H:i');

        $data = $this->ok('schedule_blog', ['slug' => $blog->slug, 'publish_at' => $at]);
        $this->assertSame($at . ' (Asia/Dubai)', $data['blog']['publish_at']);
        $this->assertSame(Carbon::parse($at, 'Asia/Dubai')->utc()->format('Y-m-d H:i'), $blog->fresh()->publish_at->utc()->format('Y-m-d H:i'));

        $this->fails('schedule_blog', ['slug' => $blog->slug, 'publish_at' => '2001-01-01 10:00']);

        $this->artisan('mcp:publish-scheduled')->assertSuccessful();
        $this->assertSame(0, (int) $blog->fresh()->getRawOriginal('status'), 'not due yet');

        Carbon::setTestNow(Carbon::now()->addHours(2));
        $this->artisan('mcp:publish-scheduled')->assertSuccessful();
        Carbon::setTestNow();
        $fresh = $blog->fresh();
        $this->assertSame(1, (int) $fresh->getRawOriginal('status'));
        $this->assertNull($fresh->publish_at);

        $this->fails('schedule_blog', ['slug' => $blog->slug, 'publish_at' => $at]);
    }

    public function test_cancel_schedule(): void
    {
        $blog = $this->blog();
        $this->ok('schedule_blog', ['slug' => $blog->slug, 'publish_at' => Carbon::now('Asia/Dubai')->addDay()->format('Y-m-d H:i')]);
        $this->assertNull($this->ok('schedule_blog', ['slug' => $blog->slug, 'publish_at' => ''])['blog']['publish_at']);
    }

    public function test_update_category_with_version_check_and_unique_slug(): void
    {
        $a = BlogCategory::create(['name' => 'MCP Cat A', 'slug' => 'mcp-cat-a-' . uniqid()]);
        $b = BlogCategory::create(['name' => 'MCP Cat B', 'slug' => 'mcp-cat-b-' . uniqid()]);

        $listed = collect($this->ok('get_categories')['categories'] ?? $this->ok('get_categories')['items'] ?? [])->firstWhere('id', $a->id);
        $stamp = $listed['updated_at'] ?? $a->fresh()->updated_at->format('Y-m-d H:i:s');

        $this->fails('update_category', ['id' => $a->id, 'expected_updated_at' => '2000-01-01 00:00:00', 'name' => 'X']);
        $this->fails('update_category', ['id' => $a->id, 'expected_updated_at' => $stamp, 'slug' => $b->slug]);

        $data = $this->ok('update_category', ['id' => $a->id, 'expected_updated_at' => $stamp, 'name' => 'Moving Tips', 'slug' => 'Moving Tips MCP ' . $a->id]);
        $this->assertSame('moving-tips-mcp-' . $a->id, $data['category']['slug']);
    }

    public function test_delete_category_moves_posts_and_can_be_restored(): void
    {
        $old = BlogCategory::create(['name' => 'Old Cat', 'slug' => 'mcp-old-cat-' . uniqid()]);
        $new = BlogCategory::create(['name' => 'New Cat', 'slug' => 'mcp-new-cat-' . uniqid()]);
        $blog = $this->blog();
        $blog->categories()->attach($old->id);

        $data = $this->ok('delete_category', ['id' => $old->id, 'move_posts_to' => $new->id]);
        $this->assertSame(1, $data['posts_added_to_target']);
        $this->assertContains($new->id, $blog->categories()->pluck('blog_categories.id')->all());
        $this->assertSame(1, (int) $old->fresh()->is_deleted);

        $ids = array_column($this->ok('get_categories')['categories'] ?? [], 'id');
        $this->assertNotContains($old->id, $ids);

        $this->ok('restore_item', ['type' => 'category', 'id' => $old->id]);
        $this->assertSame(0, (int) $old->fresh()->is_deleted);
    }

    public function test_update_delete_and_merge_tags(): void
    {
        $t1 = BlogTag::create(['name' => 'mcp dubai', 'slug' => 'mcp-dubai-' . uniqid()]);
        $t2 = BlogTag::create(['name' => 'mcp Dubai storage', 'slug' => 'mcp-dubai-storage-' . uniqid()]);
        $target = BlogTag::create(['name' => 'MCP Dubai', 'slug' => 'mcp-dubai-main-' . uniqid()]);
        $p1 = $this->blog();
        $p2 = $this->blog();
        $p1->tags()->attach([$t1->id, $target->id]);
        $p2->tags()->attach($t2->id);

        $this->ok('update_tag', ['id' => $target->id, 'expected_updated_at' => $target->fresh()->updated_at->format('Y-m-d H:i:s'), 'name' => 'Dubai']);
        $this->fails('merge_tags', ['source_ids' => [$target->id], 'target_id' => $target->id]);

        $data = $this->ok('merge_tags', ['source_ids' => [$t1->id, $t2->id], 'target_id' => $target->id]);
        $this->assertSame(1, $data['posts_newly_tagged']);
        $this->assertSame([$target->id], $p2->tags()->pluck('blog_tags.id')->all());
        $this->assertSame([$target->id], $p1->tags()->pluck('blog_tags.id')->all());
        $this->assertSame(1, (int) $t1->fresh()->is_deleted);

        $other = BlogTag::create(['name' => 'mcp delete me', 'slug' => 'mcp-del-' . uniqid()]);
        $this->ok('delete_tag', ['id' => $other->id]);
        $this->assertContains($other->id, array_column($this->ok('list_trash', ['type' => 'tag'])['items'], 'id'));
    }
}
