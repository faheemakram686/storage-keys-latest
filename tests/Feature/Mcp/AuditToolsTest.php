<?php

namespace Tests\Feature\Mcp;

class AuditToolsTest extends McpTestCase
{
    public function test_single_coded_page_seo_audit(): void
    {
        $data = $this->ok('audit_seo', ['url' => 'https://storagekeys.com/office-storage']);
        $this->assertSame(1, $data['pages_checked']);
        $page = $data['pages'][0];
        $this->assertSame('/office-storage', $page['path']);
        $this->assertSame('coded_page', $page['kind']);
        $this->assertIsInt($page['score']);
    }

    public function test_db_page_problems_are_found(): void
    {
        $page = $this->makePage([
            'status' => 1,
            'hide_banner' => 1,
            'meta_title' => 'Short',
            'meta_description' => null,
            'content' => '<section class="sk-hero"><h2>No h1 here</h2><h4>Jumped</h4><img src="/nope.jpg"></section>',
        ]);

        $issues = array_column($this->ok('audit_seo', ['url' => '/' . $page->slug])['pages'][0]['issues'], 'issue');
        $joined = implode(' | ', $issues);
        $this->assertStringContainsString('Title is 5 characters', $joined);
        $this->assertStringContainsString('0 H1 headings', $joined);
        $this->assertStringContainsString('Skipped level', $joined);
        $this->assertStringContainsString('without alt text', $joined);

        $images = $this->ok('audit_images', ['url' => '/' . $page->slug])['images'][0];
        $this->assertContains('missing alt', $images['problems']);
        $this->assertContains('not WebP (jpg)', $images['problems']);
        $this->assertContains('file not found', $images['problems']);

        $headings = $this->ok('audit_headings', ['url' => '/' . $page->slug])['pages'][0];
        $this->assertNotEmpty($headings['issues']);
    }

    public function test_other_hosts_are_refused(): void
    {
        $this->fails('audit_seo', ['url' => 'https://example.com/']);
    }

    public function test_site_wide_reports(): void
    {
        $report = $this->ok('seo_report');
        $this->assertGreaterThan(30, $report['pages']['total']);
        $this->assertArrayHasKey('coded_page', $report['pages']['by_kind']);
        $this->assertArrayHasKey('duplicate_titles', $report['meta']);

        $meta = $this->ok('audit_meta');
        $this->assertGreaterThan(30, $meta['pages_checked']);

        $thin = $this->ok('audit_thin_content', ['min_words' => 100000]);
        $this->assertSame($thin['count'], count($thin['pages']));
        $this->assertGreaterThan(30, $thin['count']);

        $anchors = $this->ok('anchor_text_report');
        $this->assertNotEmpty($anchors['targets']);

        $toOffice = $this->ok('anchor_text_report', ['target_url' => 'https://storagekeys.com/office-storage']);
        foreach ($toOffice['targets'] as $t) {
            $this->assertSame('/office-storage', $t['target']);
        }

        $this->assertArrayHasKey('pages', $this->ok('find_orphan_pages'));
        $this->assertArrayHasKey('pairs', $this->ok('audit_cannibalisation'));
        $this->assertNotEmpty($this->ok('audit_performance', ['limit' => 5])['pages']);
    }

    public function test_new_published_page_shows_up_as_orphan_after_cache_reset(): void
    {
        $this->ok('find_orphan_pages'); // warm cache
        $page = $this->makePage(['status' => 1, 'title' => 'Orphan Test']);

        $paths = array_column($this->ok('find_orphan_pages')['pages'], 'path');
        $this->assertContains('/' . $page->slug, $paths);
    }
}
