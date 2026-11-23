<?php

namespace Tests\Feature\Mcp;

use App\Models\Blog;
use App\Models\UrlRedirect;

class LinkToolsTest extends McpTestCase
{
    private function page(string $content, array $attrs = [])
    {
        return $this->makePage(array_merge(['status' => 1, 'content' => $content], $attrs));
    }

    private function blog(string $body, array $attrs = []): Blog
    {
        return Blog::create(array_merge([
            'title' => 'MCP Test Post',
            'slug' => 'mcp-test-post-' . uniqid(),
            'description' => $body,
            'image' => 'empty',
            'status' => 1,
            'is_deleted' => 0,
        ], $attrs));
    }

    public function test_add_internal_link_wraps_existing_words_and_skips_links_and_attributes(): void
    {
        $page = $this->page('<p title="car storage">We offer <a href="/x">car storage</a> and car storage in Dubai.</p>');

        $data = $this->ok('add_internal_link', ['source_slug' => $page->slug, 'anchor_text' => 'car storage', 'target_url' => 'https://storagekeys.com/car-storage']);
        $this->assertSame(
            '<p title="car storage">We offer <a href="/x">car storage</a> and <a href="/car-storage">car storage</a> in Dubai.</p>',
            $page->fresh()->content
        );
        $this->assertArrayHasKey('section_id', $data);

        $this->fails('add_internal_link', ['source_slug' => $page->slug, 'anchor_text' => 'nowhere', 'target_url' => '/car-storage']);
        $this->fails('add_internal_link', ['source_slug' => $page->slug, 'anchor_text' => 'Dubai', 'target_url' => 'javascript:alert(1)']);
    }

    public function test_coded_pages_are_refused(): void
    {
        $data = $this->fails('add_internal_link', ['source_slug' => 'office-storage', 'anchor_text' => 'files', 'target_url' => '/x']);
        $this->assertStringContainsString('Coded (Blade) pages', $data['message']);
    }

    public function test_update_and_remove_internal_link(): void
    {
        $page = $this->page('<p>See <a class="l" href="https://storagekeys.com/old-page/">our guide</a> today.</p>');

        $this->ok('update_internal_link', ['source_slug' => $page->slug, 'old_target' => '/old-page', 'new_target' => '/new-page', 'new_anchor' => 'the new guide']);
        $this->assertSame('<p>See <a class="l" href="/new-page">the new guide</a> today.</p>', $page->fresh()->content);

        $this->ok('remove_internal_link', ['source_slug' => $page->slug, 'target_url' => '/new-page']);
        $this->assertSame('<p>See the new guide today.</p>', $page->fresh()->content);
        $this->fails('remove_internal_link', ['source_slug' => $page->slug, 'target_url' => '/new-page']);
    }

    public function test_replace_text_previews_then_applies_across_pages_and_blogs(): void
    {
        $page = $this->page('<p>Call ZZQ Storage now. <a href="/zzq-storage">ZZQ Storage</a></p>');
        $blog = $this->blog('<p>ZZQ Storage is great.</p><img alt="ZZQ Storage" src="/a.webp">');

        $preview = $this->ok('replace_text', ['find' => 'ZZQ Storage', 'replace' => 'StorageKeys & Co']);
        $this->assertTrue($preview['dry_run']);
        $this->assertSame(3, $preview['total']);
        $this->assertStringContainsString('ZZQ Storage', $page->fresh()->content);

        $this->ok('replace_text', ['find' => 'ZZQ Storage', 'replace' => 'StorageKeys & Co', 'dry_run' => false]);
        $this->assertSame('<p>Call StorageKeys &amp; Co now. <a href="/zzq-storage">StorageKeys &amp; Co</a></p>', $page->fresh()->content);
        $this->assertSame('<p>StorageKeys &amp; Co is great.</p><img alt="ZZQ Storage" src="/a.webp">', $blog->fresh()->description);
    }

    public function test_replace_link_sitewide(): void
    {
        $page = $this->page('<a href="/zz-old">A</a> <a href="/zz-other">B</a>');
        $blog = $this->blog('<a href="https://storagekeys.com/zz-old?x=1">C</a>');

        $this->assertSame(2, $this->ok('replace_link_sitewide', ['old_url' => '/zz-old', 'new_url' => '/zz-new'])['total']);
        $this->ok('replace_link_sitewide', ['old_url' => '/zz-old', 'new_url' => '/zz-new', 'dry_run' => false]);

        $this->assertSame('<a href="/zz-new">A</a> <a href="/zz-other">B</a>', $page->fresh()->content);
        $this->assertSame('<a href="/zz-new">C</a>', $blog->fresh()->description);
    }

    public function test_replace_image_and_bulk_alt(): void
    {
        $page = $this->page('<img src="/img/zz-old.jpg" alt=""><img src="/img/keep.webp">');

        $this->ok('replace_image', ['old_path' => '/img/zz-old.jpg', 'new_path' => '/img/zz-new.webp', 'new_alt' => 'Storage unit', 'dry_run' => false]);
        $this->assertSame('<img src="/img/zz-new.webp" alt="Storage unit"><img src="/img/keep.webp">', $page->fresh()->content);

        $preview = $this->ok('bulk_update_alt', ['items' => [['image_path' => '/img/keep.webp', 'alt' => 'Boxes "stacked"']]]);
        $this->assertSame(1, $preview['items'][0]['images_found']);
        $this->ok('bulk_update_alt', ['items' => [['image_path' => '/img/keep.webp', 'alt' => 'Boxes "stacked"']], 'dry_run' => false]);
        $this->assertStringContainsString('<img src="/img/keep.webp" alt="Boxes &quot;stacked&quot;">', $page->fresh()->content);
    }

    public function test_update_heading_keeps_one_h1(): void
    {
        $page = $this->page('<h2>Old heading</h2><p>x</p>');

        $this->ok('update_heading', ['slug' => $page->slug, 'old_text' => 'old heading', 'new_text' => 'New heading', 'new_level' => 3]);
        $this->assertSame('<h3>New heading</h3><p>x</p>', $page->fresh()->content);

        // The page title is already the H1 (template banner)
        $this->fails('update_heading', ['slug' => $page->slug, 'old_text' => 'New heading', 'new_level' => 1]);
        $this->fails('update_heading', ['slug' => $page->slug, 'old_text' => 'Missing']);
    }

    public function test_bulk_update_seo_preview_apply_and_errors(): void
    {
        $page = $this->page('<p>x</p>');
        $blog = $this->blog('<p>y</p>');

        $items = [
            ['slug' => $page->slug, 'meta_title' => 'New page title for MCP'],
            ['slug' => '/blogs/' . $blog->slug, 'meta_description' => 'New post description', 'robots' => 'index,follow'],
        ];
        $preview = $this->ok('bulk_update_seo', ['items' => $items]);
        $this->assertCount(2, $preview['items']);
        $this->assertNull($page->fresh()->meta_title);

        $bad = $this->ok('bulk_update_seo', ['items' => array_merge($items, [['slug' => 'office-storage', 'meta_title' => 'x']]), 'dry_run' => false]);
        $this->assertFalse($bad['applied']);
        $this->assertNull($page->fresh()->meta_title);

        $this->ok('bulk_update_seo', ['items' => $items, 'dry_run' => false]);
        $this->assertSame('New page title for MCP', $page->fresh()->meta_title);
        $this->assertSame('index,follow', $blog->fresh()->robots);
    }

    public function test_changing_a_published_slug_adds_a_301(): void
    {
        $page = $this->page('<p>x</p>', ['slug' => 'mcp-old-slug-' . uniqid()]);
        $old = $page->slug;
        $this->ok('update_page', ['slug' => $old, 'new_slug' => $old . '-new']);

        $redirect = UrlRedirect::where('from_path', '/' . $old)->first();
        $this->assertNotNull($redirect);
        $this->assertSame('/' . $old . '-new', $redirect->to_url);
        $this->assertSame(301, $redirect->status_code);
        $this->get('/' . $old)->assertRedirect();
    }
}
