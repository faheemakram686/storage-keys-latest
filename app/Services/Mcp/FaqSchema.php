<?php

namespace App\Services\Mcp;

use App\Models\Page;
use App\Models\PageSection;
use App\Services\Mcp\Tools\SchemaTools;

/**
 * Keeps a DB page's FAQPage schema equal to the FAQs it shows (faq_list component).
 * Pages without a faq_list section are left alone: schema must match what visitors see.
 */
class FaqSchema
{
    /**
     * @param  list<string>  $slugs
     * @return list<string> notes for the tool result
     */
    public static function sync(array $slugs): array
    {
        $notes = [];
        foreach (array_unique(array_filter($slugs)) as $slug) {
            $page = Page::query()->active()->where('slug', $slug)->first();
            if (!$page) {
                $notes[] = '/' . $slug . ' is a coded page: its FAQ section and FAQPage schema are in the code, so this FAQ does not show there.';
                continue;
            }
            $shows = PageSection::query()->active()->where('page_id', $page->id)->where('is_visible', 1)
                ->where('type', 'component')->where('component', 'faq_list')->exists();
            if (!$shows) {
                $notes[] = 'Page /' . $slug . ' has no visible faq_list section, so the FAQ is not shown there yet. Add one: add_page_section type=component component=faq_list.';
                continue;
            }

            $faqs = BusinessBlocks::faqsFor($slug)->map(fn ($f) => [
                (string) $f->question,
                trim(preg_replace('/\s+/u', ' ', html_entity_decode(strip_tags((string) $f->answer), ENT_QUOTES | ENT_HTML5, 'UTF-8'))),
            ])->all();

            $nodes = array_values(array_filter(SchemaRules::nodes($page->schemaArray()), fn ($n) => SchemaRules::typeOf($n) !== 'FAQPage'));
            if ($faqs) {
                $nodes[] = SchemaTools::faqNode($faqs);
            }
            $page->schema_json = $nodes === [] ? null : json_encode(['@context' => 'https://schema.org', '@graph' => $nodes], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
            if ($page->isDirty('schema_json')) {
                $page->save();
                $notes[] = 'FAQPage schema of /' . $slug . ' updated (' . count($faqs) . ' question(s)).';
            }
        }

        return $notes;
    }
}
