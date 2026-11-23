<?php

namespace Tests\Feature\Mcp;

use App\Models\Blog;
use App\Services\Mcp\RobotsTxt;

class SchemaToolsTest extends McpTestCase
{
    private function faqPage()
    {
        return $this->makePage([
            'status' => 1,
            'title' => 'Storage Help',
            'meta_description' => 'Answers about storage units.',
            'content' => '<h2>Questions</h2><details><summary>How big is a unit?</summary><div>From 25 to 300 sq ft.</div></details>'
                . '<h3>Can I visit any time?</h3><p>Yes, 24/7 access.</p>',
        ]);
    }

    public function test_generate_preview_for_coded_page_does_not_save(): void
    {
        $data = $this->ok('generate_schema', ['slug' => '/office-storage', 'type' => 'Service']);
        $this->assertFalse($data['saved']);
        $this->assertSame('Service', $data['schema']['@type']);
        $this->assertStringContainsString('Office Storage', $data['schema']['name']);

        $this->fails('generate_schema', ['slug' => '/office-storage', 'type' => 'Service', 'save' => true]);
    }

    public function test_faq_schema_from_page_content_is_saved_and_rendered(): void
    {
        $page = $this->faqPage();
        $data = $this->ok('generate_schema', ['slug' => $page->slug, 'type' => 'FAQPage', 'save' => true]);
        $this->assertTrue($data['saved']);
        $names = array_column($data['schema']['mainEntity'], 'name');
        $this->assertSame(['How big is a unit?', 'Can I visit any time?'], $names);

        $html = $this->get('/' . $page->slug)->assertOk()->getContent();
        $this->assertStringContainsString('"FAQPage"', $html);
        $this->assertStringContainsString('From 25 to 300 sq ft.', $html);

        // Same type again replaces, never duplicates.
        $this->ok('generate_schema', ['slug' => $page->slug, 'type' => 'FAQPage', 'save' => true]);
        $this->ok('generate_schema', ['slug' => $page->slug, 'type' => 'BreadcrumbList', 'save' => true]);
        $types = array_column(json_decode($page->fresh()->schema_json, true)['@graph'], '@type');
        $this->assertSame(['FAQPage', 'BreadcrumbList'], $types);

        $this->ok('delete_schema', ['slug' => $page->slug, 'type' => 'FAQPage']);
        $this->assertSame(['BreadcrumbList'], array_column(json_decode($page->fresh()->schema_json, true)['@graph'], '@type'));
        $this->fails('delete_schema', ['slug' => $page->slug, 'type' => 'FAQPage']);
    }

    public function test_self_storage_uses_real_facility_and_needs_city(): void
    {
        $page = $this->faqPage();
        $this->fails('generate_schema', ['slug' => $page->slug, 'type' => 'SelfStorage']);

        $node = $this->ok('generate_schema', ['slug' => $page->slug, 'type' => 'SelfStorage', 'city' => 'dubai'])['schema'];
        $this->assertSame('10A Street, Al Quoz Industrial Area 3', $node['address']['streetAddress']);
        $this->assertSame('https://storagekeys.com/#storagekeys-dubai', $node['@id']);
        $this->assertArrayNotHasKey('aggregateRating', $node);
    }

    public function test_type_already_output_by_template_is_refused(): void
    {
        // The Dubai CMS override keeps the template's SelfStorage + BreadcrumbList blocks.
        $this->ok('create_page', ['title' => 'Self Storage in Dubai', 'slug' => 'self-storage-dubai', 'content' => '<p>MCP Dubai test</p>', 'status' => 1]);
        $data = $this->fails('generate_schema', ['slug' => 'self-storage-dubai', 'type' => 'SelfStorage', 'save' => true]);
        $this->assertStringContainsString('already outputs', $data['message']);
    }

    public function test_reviews_and_ratings_are_refused_in_blog_schema(): void
    {
        $blog = Blog::create(['title' => 'Schema post', 'slug' => 'mcp-schema-' . uniqid(), 'description' => '<p>x</p>', 'image' => 'empty', 'status' => 1, 'is_deleted' => 0]);

        $this->fails('generate_schema', ['slug' => '/blogs/' . $blog->slug, 'type' => 'Review']);
        $bad = $this->fails('update_schema', ['slug' => $blog->slug, 'schema' => ['@context' => 'https://schema.org', '@type' => 'LocalBusiness', 'name' => 'StorageKeys', 'aggregateRating' => ['@type' => 'AggregateRating', 'ratingValue' => 5]]]);
        $this->assertNotEmpty($bad['problems']);
        $dupe = $this->fails('update_schema', ['slug' => $blog->slug, 'schema' => ['@graph' => [['@type' => 'Article'], ['@type' => 'Article']]]]);
        $this->assertNotEmpty($dupe['problems']);

        $this->ok('update_schema', ['slug' => $blog->slug, 'schema' => ['@context' => 'https://schema.org', '@type' => 'Article', 'headline' => 'Schema post']]);
    }

    public function test_robots_is_served_by_laravel_and_validated(): void
    {
        $this->assertFileDoesNotExist(public_path('robots.txt'));
        $this->get('/robots.txt')->assertOk()->assertSee('Sitemap: https://storagekeys.com/sitemap.xml')->assertSee('Disallow: /mcp-upload/');

        $this->fails('update_robots', ['content' => "User-agent: *\nDisallow: /\nSitemap: https://storagekeys.com/sitemap.xml"]);
        $this->fails('update_robots', ['content' => "User-agent: *\nDisallow: /admin"]);
        $this->fails('update_robots', ['content' => "Disallow: /admin\nSitemap: https://storagekeys.com/sitemap.xml"]);

        $new = rtrim(RobotsTxt::default()) . "\nDisallow: /zz-mcp-test\n";
        $saved = $this->ok('update_robots', ['content' => $new]);
        $this->assertSame([], $saved['warnings']);
        $this->get('/robots.txt')->assertSee('Disallow: /zz-mcp-test');

        $warn = $this->ok('update_robots', ['content' => rtrim(RobotsTxt::default()) . "\nDisallow: /personal-storage\n"]);
        $this->assertNotEmpty($warn['warnings']);

        $log = $this->ok('activity_log', ['entity_type' => 'robots']);
        $this->assertSame('update_robots', $log['entries'][0]['tool']);

        $this->assertTrue($this->ok('get_robots')['errors'] === []);
    }
}
