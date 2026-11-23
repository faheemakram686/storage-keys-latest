<?php

namespace Tests\Feature\Mcp;

use App\Models\Blog;
use App\Models\Inquiry;
use App\Models\Office;

class BusinessToolsTest extends McpTestCase
{
    public function test_settings_defaults_update_and_placeholders(): void
    {
        $settings = collect($this->ok('get_settings')['settings'])->keyBy('key');
        $this->assertSame('+971 56 501 8785', $settings['phone']['value']);

        $page = $this->makePage(['status' => 1, 'content' => '<p>Call {{phone}} ({{phone_raw}}), mail {{email}}, <a href="{{wa_link}}">WhatsApp</a>. {{hours}}. {{setting:stat_units}}</p>']);
        $this->get('/' . $page->slug)->assertSee('Call +971 56 501 8785 (+971565018785), mail sales@storagekeys.com', false)->assertSee('href="https://wa.me/971565018785"', false);

        $this->fails('update_settings', ['key' => 'email', 'value' => 'not-an-email']);
        $this->fails('update_settings', ['key' => 'whatsapp', 'value' => '+971 56']);
        $this->fails('update_settings', ['key' => 'favourite_colour', 'value' => 'blue']);

        $this->ok('update_settings', ['key' => 'phone', 'value' => '+971 4 000 0000']);
        $this->ok('update_settings', ['key' => 'stat_units', 'value' => '1,200 units']);
        $this->get('/' . $page->slug)->assertSee('Call +971 4 000 0000 (+97140000000)', false)->assertSee('1,200 units');

        $reset = $this->ok('update_settings', ['key' => 'phone', 'value' => '']);
        $this->assertTrue($reset['reset_to_default']);
        $this->assertSame('setting', $this->ok('activity_log', ['entity_type' => 'setting'])['entries'][0]['entity_type']);
    }

    public function test_unknown_placeholders_are_refused_on_write(): void
    {
        $page = $this->makePage();
        $data = $this->fails('add_page_section', ['page_slug' => $page->slug, 'type' => 'html', 'content' => '<p>{{price}} and {{address:mars}}</p>']);
        $this->assertSame(['{{price}}', '{{address:mars}}'], $data['unknown_placeholders']);

        $this->fails('create_page', ['title' => 'Bad placeholder page', 'content' => '<p>Hello {{ nope }} world</p>']);
        $this->fails('create_blog', ['title' => 'Bad placeholder post', 'description' => '<p>{{setting:bad key}}</p>']);
        $this->ok('add_page_section', ['page_slug' => $page->slug, 'type' => 'html', 'content' => '<p>{{address:dubai}}</p>']);
    }

    public function test_blog_body_renders_placeholders(): void
    {
        $blog = Blog::create(['title' => 'Placeholder post', 'slug' => 'mcp-ph-' . uniqid(), 'description' => '<p>Email {{email}}</p>', 'image' => 'empty', 'status' => 1, 'is_deleted' => 0]);
        $this->get('/blogs/' . $blog->slug)->assertOk()->assertSee('Email sales@storagekeys.com');
    }

    public function test_offices_are_seeded_and_feed_location_pages(): void
    {
        $offices = collect($this->ok('list_offices')['offices'])->keyBy('key');
        $this->assertSame('10A Street, Al Quoz Industrial Area 3', $offices['dubai']['street']);
        $this->assertSame('{{address:sharjah}}', $offices['sharjah']['placeholder']);

        $this->fails('update_office', ['id' => $offices['dubai']['id'], 'expected_updated_at' => '2000-01-01 00:00:00', 'street' => 'x']);
        $this->ok('update_office', ['id' => $offices['dubai']['id'], 'expected_updated_at' => $offices['dubai']['updated_at'], 'street' => '12B Test Street, Al Quoz']);

        $html = $this->get('/self-storage-dubai')->assertOk()->getContent();
        $this->assertStringContainsString('12B Test Street, Al Quoz', $html);
        $this->assertStringContainsString('"streetAddress":"12B Test Street, Al Quoz"', $html);

        $page = $this->makePage(['status' => 1, 'content' => '<p>Visit us: {{address:dubai}}</p>']);
        $this->get('/' . $page->slug)->assertSee('Visit us: 12B Test Street, Al Quoz, Al Quoz, Dubai, UAE');

        $this->ok('delete_office', ['id' => $offices['dubai']['id']]);
        $this->get('/self-storage-dubai')->assertSee('10A Street, Al Quoz Industrial Area 3');

        $this->ok('create_office', ['key' => 'abu_dhabi', 'name' => 'Test Office', 'street' => 'Street 1', 'locality' => 'Mussafah', 'region' => 'Abu Dhabi']);
        $this->fails('create_office', ['key' => 'abu_dhabi', 'name' => 'Dup', 'street' => 's', 'locality' => 'l', 'region' => 'r']);
        $this->assertNotNull(Office::where('key', 'abu_dhabi')->first());
    }

    public function test_faqs_render_through_component_and_keep_schema_in_sync(): void
    {
        $page = $this->makePage(['status' => 1, 'hide_banner' => 0, 'content' => '<p>Intro</p>']);

        $draft = $this->ok('create_faq', ['question' => 'Do you have parking?', 'answer' => 'Yes, free parking at {{address:dubai}}.', 'page_slugs' => [$page->slug]]);
        $this->assertFalse($draft['faq']['published']);
        $this->assertStringContainsString('no visible faq_list section', implode(' ', $draft['schema']));

        $this->ok('add_page_section', ['page_slug' => $page->slug, 'type' => 'component', 'component' => 'faq_list']);
        $this->get('/' . $page->slug)->assertDontSee('Do you have parking?');

        $pub = $this->ok('update_faq', ['id' => $draft['faq']['id'], 'expected_updated_at' => $draft['faq']['updated_at'], 'published' => true]);
        $this->assertStringContainsString('FAQPage schema', implode(' ', $pub['schema']));
        $html = $this->get('/' . $page->slug)->getContent();
        $this->assertStringContainsString('<summary>Do you have parking?</summary>', $html);
        $this->assertStringContainsString('free parking at 10A Street', $html);
        $this->assertStringContainsString('"FAQPage"', $html);

        $second = $this->ok('create_faq', ['question' => 'Is it insured?', 'answer' => 'Ask our team.', 'page_slugs' => [$page->slug, 'office-storage'], 'published' => true]);
        $this->assertStringContainsString('coded page', implode(' ', $second['schema']));
        $graph = json_decode($page->fresh()->schema_json, true)['@graph'];
        $this->assertCount(2, $graph[0]['mainEntity']);

        $this->ok('delete_faq', ['id' => $draft['faq']['id']]);
        $graph = json_decode($page->fresh()->schema_json, true)['@graph'];
        $this->assertSame(['Is it insured?'], array_column($graph[0]['mainEntity'], 'name'));

        $this->ok('restore_item', ['type' => 'faq', 'id' => $draft['faq']['id']]);
        $this->assertCount(2, json_decode($page->fresh()->schema_json, true)['@graph'][0]['mainEntity']);
        $this->assertCount(2, $this->ok('list_faqs', ['page_slug' => $page->slug])['faqs']);
    }

    public function test_testimonials_are_drafts_until_published_and_never_schema(): void
    {
        $page = $this->makePage(['status' => 1, 'content' => '<p>x</p>']);
        $this->ok('add_page_section', ['page_slug' => $page->slug, 'type' => 'component', 'component' => 'testimonials']);

        $t = $this->ok('create_testimonial', ['name' => 'Aisha K.', 'text' => 'Great, careful movers.', 'source' => 'Google', 'date' => '2026-10-01'])['testimonial'];
        $this->get('/' . $page->slug)->assertDontSee('Great, careful movers.');
        $this->fails('create_testimonial', ['name' => 'X', 'text' => 'Y']); // no source
        $this->fails('create_testimonial', ['name' => 'X', 'text' => 'Y', 'source' => 'Google', 'date' => '2999-01-01']);

        $this->ok('update_testimonial', ['id' => $t['id'], 'expected_updated_at' => $t['updated_at'], 'published' => true]);
        $html = $this->get('/' . $page->slug)->assertSee('Great, careful movers.')->assertSee('Aisha K.')->getContent();
        $this->assertStringNotContainsString('"Review"', $html);
        $this->assertStringNotContainsString('aggregateRating', $html);
    }

    public function test_pricing_plans_and_plan_placeholder(): void
    {
        $page = $this->makePage(['status' => 1, 'content' => '<p>Small unit from {{plan:mcp-small}}</p>']);
        $this->get('/' . $page->slug)->assertSee('Small unit from Ask for a quote');

        $plan = $this->ok('create_pricing_plan', ['slug' => 'mcp-small', 'name' => 'Small unit', 'unit_size' => '5 x 5 ft', 'price' => 450, 'features' => ['Climate controlled']])['plan'];
        $this->assertSame('AED 450 / month', $plan['price_text']);
        $this->get('/' . $page->slug)->assertSee('Small unit from Ask for a quote'); // draft

        $plan = $this->ok('update_pricing_plan', ['id' => $plan['id'], 'expected_updated_at' => $plan['updated_at'], 'published' => true])['plan'];
        $this->get('/' . $page->slug)->assertSee('Small unit from AED 450 / month');

        $this->ok('add_page_section', ['page_slug' => $page->slug, 'type' => 'component', 'component' => 'pricing_table']);
        $this->get('/' . $page->slug)->assertSee('5 x 5 ft')->assertSee('Climate controlled');

        $this->fails('create_pricing_plan', ['slug' => 'mcp-small', 'name' => 'Dup']);
        $this->fails('update_pricing_plan', ['id' => $plan['id'], 'expected_updated_at' => $plan['updated_at'], 'price' => -5]);
        $noPrice = $this->ok('update_pricing_plan', ['id' => $plan['id'], 'expected_updated_at' => $plan['updated_at'], 'price' => null])['plan'];
        $this->assertSame('Ask for a quote', $noPrice['price_text']);
    }

    public function test_embed_places_a_component_inside_html(): void
    {
        $page = $this->makePage(['status' => 1, 'content' => '<p>x</p>']);
        $form = $this->ok('add_page_section', ['page_slug' => $page->slug, 'type' => 'component', 'component' => 'quote_form', 'attrs' => ['title' => 'Embedded MCP quote']])['section'];
        $this->ok('toggle_section_visibility', ['section_id' => $form['id'], 'visible' => false]);
        $this->get('/' . $page->slug)->assertDontSee('Embedded MCP quote');

        $this->ok('add_page_section', ['page_slug' => $page->slug, 'type' => 'html', 'content' => '<div class="two-col"><p>Text</p>{{embed:' . $form['id'] . '}}</div>']);
        $this->get('/' . $page->slug)->assertSee('Embedded MCP quote');
        $this->fails('add_page_section', ['page_slug' => $page->slug, 'type' => 'html', 'content' => '<p>{{embed:999999}}</p>']);
    }

    public function test_enquiries_are_listed_read_only(): void
    {
        $i = Inquiry::create(['name' => 'MCP Lead', 'email' => 'lead@example.com', 'phone' => '0500000000', 'storage_type' => 'Personal Storage', 'message' => 'Need a unit']);
        $rows = $this->ok('list_enquiries', ['limit' => 5])['enquiries'];
        $this->assertSame($i->id, $rows[0]['id']);
        $this->assertSame('MCP Lead', $rows[0]['name']);

        $none = $this->ok('list_enquiries', ['from' => '2000-01-01', 'to' => '2000-01-02']);
        $this->assertSame(0, $none['count']);
        $this->fails('list_enquiries', ['from' => 'yesterday-ish']);
    }
}
