<?php

namespace Tests\Feature\Mcp;

use App\Models\Page;
use App\Models\PageSection;
use App\Services\Mcp\HtmlSanitizer;

class SectionToolsTest extends McpTestCase
{
    private function publishedPage(string $content = '<section><h2>Intro</h2><p>Original text here.</p></section>'): Page
    {
        return $this->makePage(['status' => 1, 'content' => $content, 'slug' => 'mcp-sections-' . uniqid()]);
    }

    public function test_existing_content_becomes_one_section_and_renders_the_same(): void
    {
        $content = "<section><h2>Intro</h2><p>Original text here.</p></section>\n<p>Second part</p>";
        $page = $this->publishedPage($content);
        $before = $this->get('/' . $page->slug)->assertOk()->getContent();

        $data = $this->ok('get_page_sections', ['page_slug' => $page->slug]);
        $this->assertSame(1, $data['count']);
        $this->assertSame($content, $data['sections'][0]['content']);
        $this->assertSame($content, $page->fresh()->content);

        $after = $this->get('/' . $page->slug)->assertOk()->getContent();
        $this->assertSame($this->stripTokens($before), $this->stripTokens($after));
    }

    public function test_add_update_and_render(): void
    {
        $page = $this->publishedPage();
        $added = $this->ok('add_page_section', ['page_slug' => $page->slug, 'type' => 'html', 'content' => '<h2>Prices</h2><p>Ask for a quote.</p>']);
        $this->assertSame('Prices', $added['section']['name']);
        $this->assertSame(2, $added['section']['position']);

        $this->get('/' . $page->slug)->assertSee('Original text here.')->assertSee('Ask for a quote.');

        $updated = $this->ok('update_page_section', [
            'section_id' => $added['section']['id'],
            'expected_updated_at' => $added['section']['updated_at'],
            'find' => 'Ask for a quote.',
            'replace' => 'Ask us for a free quote.',
        ]);
        $this->assertStringContainsString('Ask us for a free quote.', $updated['section']['content']);
        $this->get('/' . $page->slug)->assertSee('Ask us for a free quote.')->assertDontSee('Ask for a quote.');
    }

    public function test_version_conflict_and_missing_version_are_refused(): void
    {
        $page = $this->publishedPage();
        $section = $this->ok('get_page_sections', ['page_slug' => $page->slug])['sections'][0];

        $this->ok('update_page_section', ['section_id' => $section['id'], 'expected_updated_at' => $section['updated_at'], 'content' => '<p>Edit one</p>']);

        $stale = $this->fails('update_page_section', ['section_id' => $section['id'], 'expected_updated_at' => $section['updated_at'], 'content' => '<p>Edit two</p>']);
        $this->assertStringContainsString('changed since you read it', $stale['message']);
        $this->assertArrayHasKey('current_updated_at', $stale);

        $missing = $this->fails('update_page_section', ['section_id' => $section['id'], 'content' => '<p>Edit two</p>']);
        $this->assertStringContainsString('expected_updated_at is required', $missing['message']);
        $this->assertStringContainsString('Edit one', $page->fresh()->content);
    }

    public function test_find_must_match_exactly_once_unless_occurrence_given(): void
    {
        $page = $this->publishedPage('<p>Box</p><p>Box</p>');
        $s = $this->ok('get_page_sections', ['page_slug' => $page->slug])['sections'][0];

        $this->assertSame(2, $this->fails('update_page_section', ['section_id' => $s['id'], 'expected_updated_at' => $s['updated_at'], 'find' => 'Box', 'replace' => 'Crate'])['matches']);
        $this->fails('update_page_section', ['section_id' => $s['id'], 'expected_updated_at' => $s['updated_at'], 'find' => 'Nope', 'replace' => 'x']);

        $this->ok('update_page_section', ['section_id' => $s['id'], 'expected_updated_at' => $s['updated_at'], 'find' => 'Box', 'replace' => 'Crate', 'occurrence' => 2]);
        $this->assertSame('<p>Box</p><p>Crate</p>', $page->fresh()->content);
    }

    public function test_html_is_sanitized_and_json_ld_kept(): void
    {
        $page = $this->publishedPage();
        $html = '<p onclick="steal()">Hi</p><script>alert(1)</script><a href="javascript:alert(2)">x</a>'
            . '<script type="application/ld+json">{"@type":"Thing"}</script><form><input name="q"></form>'
            . '<iframe src="https://evil.example/x"></iframe><iframe src="https://www.google.com/maps/embed?pb=1"></iframe>';

        $data = $this->ok('add_page_section', ['page_slug' => $page->slug, 'type' => 'html', 'content' => $html]);
        $stored = PageSection::find($data['section']['id'])->html;

        $this->assertStringNotContainsString('onclick', $stored);
        $this->assertStringNotContainsString('alert(1)', $stored);
        $this->assertStringNotContainsString('javascript:', $stored);
        $this->assertStringNotContainsString('<form', $stored);
        $this->assertStringNotContainsString('evil.example', $stored);
        $this->assertStringContainsString('application/ld+json', $stored);
        $this->assertStringContainsString('google.com/maps', $stored);
        $this->assertNotEmpty($data['sanitized_removed']);
    }

    public function test_clean_html_is_not_reformatted(): void
    {
        $html = "<section class=\"x\" data-a='1'>\n  <style>.x > p{color:red}</style><p>Text &amp; more</p>\n</section>";
        $this->assertSame($html, HtmlSanitizer::clean($html)['html']);
    }

    public function test_a_second_h1_is_refused(): void
    {
        $page = $this->publishedPage(); // banner shows the title as H1
        $data = $this->fails('add_page_section', ['page_slug' => $page->slug, 'type' => 'html', 'content' => '<h1>Another title</h1>']);
        $this->assertStringContainsString('H1', $data['message']);
    }

    public function test_component_section_renders_the_quote_form(): void
    {
        $page = $this->publishedPage();
        $this->fails('add_page_section', ['page_slug' => $page->slug, 'type' => 'component', 'component' => 'nope']);
        $this->fails('add_page_section', ['page_slug' => $page->slug, 'type' => 'component', 'component' => 'quote_form', 'attrs' => ['variant' => 'bad']]);

        $this->ok('add_page_section', ['page_slug' => $page->slug, 'type' => 'component', 'component' => 'quote_form', 'attrs' => ['variant' => 'simple', 'title' => 'Quote for MCP']]);
        $this->assertStringContainsString('<!--sk-component:quote_form', $page->fresh()->content);
        $this->get('/' . $page->slug)->assertSee('Quote for MCP')->assertSee('name="_token"', false);

        $this->assertContains('quote_form', array_column($this->ok('list_components')['components'], 'name'));
    }

    public function test_delete_restore_reorder_and_toggle(): void
    {
        $page = $this->publishedPage('<p>A</p>');
        $first = $this->ok('get_page_sections', ['page_slug' => $page->slug])['sections'][0];
        $b = $this->ok('add_page_section', ['page_slug' => $page->slug, 'type' => 'html', 'content' => '<p>B</p>'])['section'];
        $this->assertSame("<p>A</p>\n<p>B</p>", $page->fresh()->content);

        $this->fails('reorder_page_sections', ['page_slug' => $page->slug, 'section_ids' => [$b['id']]]);
        $this->ok('reorder_page_sections', ['page_slug' => $page->slug, 'section_ids' => [$b['id'], $first['id']]]);
        $this->assertSame("<p>B</p>\n<p>A</p>", $page->fresh()->content);

        $this->ok('toggle_section_visibility', ['section_id' => $b['id'], 'visible' => false]);
        $this->assertSame('<p>A</p>', $page->fresh()->content);
        $this->ok('toggle_section_visibility', ['section_id' => $b['id'], 'visible' => true]);

        $b = PageSection::find($b['id']);
        $this->ok('delete_page_section', ['section_id' => $b->id, 'expected_updated_at' => $b->updated_at->format('Y-m-d H:i:s')]);
        $this->assertSame('<p>A</p>', $page->fresh()->content);
        $this->assertContains($b->id, array_column($this->ok('list_trash', ['type' => 'section'])['items'], 'id'));

        $this->ok('restore_item', ['type' => 'section', 'id' => $b->id]);
        $this->assertSame("<p>A</p>\n<p>B</p>", $page->fresh()->content);
    }

    public function test_revisions_can_be_listed_and_restored(): void
    {
        $page = $this->publishedPage('<p>Version one</p>');
        $s = $this->ok('get_page_sections', ['page_slug' => $page->slug])['sections'][0];
        $this->ok('update_page_section', ['section_id' => $s['id'], 'expected_updated_at' => $s['updated_at'], 'content' => '<p>Version two</p>']);

        $revs = $this->ok('list_section_revisions', ['section_id' => $s['id']])['revisions'];
        $this->assertSame(['updated', 'created'], array_column($revs, 'event'));
        $this->assertSame('<p>Version one</p>', $revs[0]['changes']['html']['before']);

        $this->ok('restore_section_revision', ['section_id' => $s['id'], 'revision_id' => $revs[1]['revision_id']]);
        $this->assertSame('<p>Version one</p>', $page->fresh()->content);
        $this->assertCount(3, $this->ok('list_section_revisions', ['section_id' => $s['id']])['revisions']);
    }

    public function test_old_update_page_keeps_sections_in_step(): void
    {
        $page = $this->publishedPage('<p>A</p>');
        $this->ok('add_page_section', ['page_slug' => $page->slug, 'type' => 'html', 'content' => '<p>B</p>']);

        $data = $this->ok('update_page', ['slug' => $page->slug, 'content' => '<p>Whole new page</p>']);
        $this->assertArrayHasKey('note', $data);

        $sections = $this->ok('get_page_sections', ['page_slug' => $page->slug]);
        $this->assertSame(1, $sections['count']);
        $this->assertSame('<p>Whole new page</p>', $sections['sections'][0]['content']);
        $this->assertArrayNotHasKey('warnings', $sections);
    }

    public function test_update_page_with_stale_expected_updated_at_is_refused(): void
    {
        $page = $this->publishedPage();
        $data = $this->fails('update_page', ['slug' => $page->slug, 'title' => 'New', 'expected_updated_at' => '2000-01-01 00:00:00']);
        $this->assertStringContainsString('changed since you read it', $data['message']);
    }

    public function test_outline_for_db_and_coded_pages(): void
    {
        $page = $this->publishedPage('<h2>Intro</h2><p>One two three</p><h4>Deep</h4>');
        $outline = $this->ok('get_page_outline', ['slug' => $page->slug]);
        $this->assertSame('db_page', $outline['kind']);
        $this->assertSame([1, 2], array_column($outline['headings'], 'level'));
        $this->assertNotNull($outline['headings'][1]['section_id']);

        $coded = $this->ok('get_page_outline', ['slug' => '/office-storage']);
        $this->assertSame('coded_page', $coded['kind']);
        $this->assertGreaterThan(100, $coded['words']);
        $this->assertSame(1, count(array_filter($coded['headings'], fn ($h) => $h['level'] === 1)));
    }

    public function test_coded_page_has_no_sections(): void
    {
        $this->assertStringContainsString('Coded (Blade) pages have no sections', $this->fails('get_page_sections', ['page_slug' => 'office-storage'])['message']);
    }

    public function test_dubai_override_still_works(): void
    {
        $this->get('/self-storage-dubai')->assertOk()->assertSee('Secure Units in Al Quoz');

        $this->ok('create_page', ['title' => 'Self Storage in Dubai', 'slug' => 'self-storage-dubai', 'content' => '<p>MCP Dubai content block</p>', 'status' => 0]);
        $this->get('/self-storage-dubai')->assertSee('Secure Units in Al Quoz')->assertDontSee('MCP Dubai content block');

        $this->ok('update_page', ['slug' => 'self-storage-dubai', 'status' => 1]);
        $this->get('/self-storage-dubai')->assertSee('MCP Dubai content block')->assertDontSee('Secure Units in Al Quoz');

        $s = $this->ok('get_page_sections', ['page_slug' => 'self-storage-dubai'])['sections'][0];
        $this->ok('update_page_section', ['section_id' => $s['id'], 'expected_updated_at' => $s['updated_at'], 'find' => 'block', 'replace' => 'section']);
        $this->get('/self-storage-dubai')->assertSee('MCP Dubai content section');
    }

    private function stripTokens(string $html): string
    {
        return preg_replace('/(name="_token" value=|csrf-token" content=)"[^"]*"/', '$1""', $html);
    }
}
