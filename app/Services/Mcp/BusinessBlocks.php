<?php

namespace App\Services\Mcp;

use App\Models\Faq;
use App\Models\Page;
use App\Models\PricingPlan;
use App\Models\Testimonial;
use Illuminate\Support\Collection;

/**
 * HTML for the faq_list, testimonials and pricing_table components, using the site's existing
 * section and FAQ classes so they look like the coded pages.
 */
class BusinessBlocks
{
    private static function e($v): string
    {
        return htmlspecialchars((string) $v, ENT_QUOTES | ENT_HTML5, 'UTF-8');
    }

    /** Published FAQs of a page slug, in order. */
    public static function faqsFor(string $slug): Collection
    {
        return Faq::query()->where('is_deleted', 0)->where('is_published', 1)
            ->whereJsonContains('page_slugs', $slug)
            ->orderBy('position')->orderBy('id')->get();
    }

    public static function faqList(array $vars, ?int $pageId): string
    {
        $slug = $vars['page'] ?? ($pageId ? Page::query()->whereKey($pageId)->value('slug') : null);
        $faqs = $slug ? self::faqsFor((string) $slug) : collect();
        if ($faqs->isEmpty()) {
            return '';
        }
        $out = '<section class="sk-section"><div class="sk-container">'
            . '<div class="sk-section-head"><span class="sk-eyebrow sk-eyebrow--center">FAQs</span><h2>' . self::e($vars['title'] ?? 'Frequently Asked Questions') . '</h2></div>'
            . '<div class="lt-faq">';
        foreach ($faqs as $i => $faq) {
            $out .= '<details' . ($i === 0 ? ' open' : '') . '><summary>' . self::e($faq->question) . '</summary><div class="a">'
                . HtmlSanitizer::clean((string) $faq->answer)['html'] . '</div></details>';
        }

        return $out . '</div></div></section>';
    }

    public static function testimonials(array $vars, ?int $pageId): string
    {
        $limit = max(1, min(24, (int) ($vars['limit'] ?? 6)));
        $items = Testimonial::query()->where('is_deleted', 0)->where('is_published', 1)
            ->orderByDesc('review_date')->orderByDesc('id')->limit($limit)->get();
        if ($items->isEmpty()) {
            return '';
        }
        $out = '<section class="sk-section"><div class="sk-container">'
            . '<div class="sk-section-head"><h2>' . self::e($vars['title'] ?? 'What Our Customers Say') . '</h2></div><div class="sk-grid sk-testimonials">';
        foreach ($items as $t) {
            $out .= '<figure class="sk-card sk-testimonial"><blockquote>' . nl2br(self::e($t->text)) . '</blockquote><figcaption><strong>'
                . self::e($t->name) . '</strong> <span>' . self::e($t->source) . ($t->review_date ? ', ' . $t->review_date->format('M Y') : '') . '</span></figcaption></figure>';
        }

        return $out . '</div></div></section>';
    }

    public static function pricingTable(array $vars, ?int $pageId): string
    {
        $q = PricingPlan::query()->where('is_deleted', 0)->where('is_published', 1)->orderBy('position')->orderBy('id');
        if (!empty($vars['plans'])) {
            $q->whereIn('slug', array_map('trim', explode(',', (string) $vars['plans'])));
        }
        $plans = $q->get();
        if ($plans->isEmpty()) {
            return '';
        }
        $out = '<section class="sk-section"><div class="sk-container">'
            . '<div class="sk-section-head"><h2>' . self::e($vars['title'] ?? 'Storage Unit Prices') . '</h2></div><div class="sk-grid sk-pricing">';
        foreach ($plans as $p) {
            $out .= '<div class="sk-card sk-plan"><h3>' . self::e($p->name) . '</h3>'
                . ($p->unit_size ? '<p class="sk-plan-size">' . self::e($p->unit_size) . '</p>' : '')
                . '<p class="sk-plan-price"><strong>' . self::e($p->priceText()) . '</strong></p>';
            if ($p->features) {
                $out .= '<ul>' . implode('', array_map(fn ($f) => '<li>' . self::e($f) . '</li>', (array) $p->features)) . '</ul>';
            }
            $out .= '</div>';
        }

        return $out . '</div></div></section>';
    }
}
