<?php

namespace App\Services\Mcp;

use App\Models\Office;
use App\Models\PageSection;
use App\Models\PricingPlan;
use Illuminate\Support\Facades\Schema;

/**
 * {{placeholders}} in DB pages and blog posts, filled in when the page is shown:
 * {{phone}} {{phone_raw}} {{email}} {{whatsapp}} {{wa_link}} {{toll_free}} {{hours}}
 * {{address:dubai}} {{address:sharjah}} (any office key), {{setting:key}}, {{plan:slug}},
 * {{embed:ID}} (a component section of the same page, by its id; hide that section to show it only here).
 * Writes with any other {{...}} are refused.
 */
class Placeholders
{
    public const PATTERN = '/\{\{\s*([a-z_]+)(?::([a-z0-9_.-]+))?\s*\}\}/i';
    private const SIMPLE = ['phone', 'phone_raw', 'email', 'whatsapp', 'wa_link', 'toll_free', 'hours'];
    private const WITH_ARG = ['address', 'setting', 'plan', 'embed'];

    /** Placeholders in $html that would not render. @return list<string> */
    public static function unknown(string $html): array
    {
        if (!str_contains($html, '{{')) {
            return [];
        }
        $bad = [];
        preg_match_all(self::PATTERN, $html, $m, PREG_SET_ORDER);
        foreach ($m as $match) {
            [$full, $name] = [$match[0], strtolower($match[1])];
            $arg = $match[2] ?? '';
            if (in_array($name, self::SIMPLE, true) && $arg === '') {
                continue;
            }
            if (!in_array($name, self::WITH_ARG, true) || $arg === '') {
                $bad[] = $full;
                continue;
            }
            $ok = match ($name) {
                'setting' => SiteSettings::validKey($arg),
                'address' => Schema::hasTable('offices') && Office::query()->where('is_deleted', 0)->where('key', $arg)->exists(),
                'plan' => Schema::hasTable('pricing_plans') && PricingPlan::query()->where('is_deleted', 0)->where('slug', $arg)->exists(),
                'embed' => ctype_digit($arg) && PageSection::query()->active()->where('type', 'component')->whereKey((int) $arg)->exists(),
                default => false,
            };
            if (!$ok) {
                $bad[] = $full;
            }
        }
        // {{ ... }} that does not even match the pattern
        preg_match_all('/\{\{(.*?)\}\}/s', $html, $all);
        foreach ($all[0] as $token) {
            if (!preg_match(self::PATTERN, $token)) {
                $bad[] = $token;
            }
        }

        return array_values(array_unique($bad));
    }

    public static function assertKnown(string $html): void
    {
        if ($bad = self::unknown($html)) {
            throw new McpException(
                'Unknown placeholder(s): ' . implode(', ', $bad) . '. Allowed: {{' . implode('}}, {{', self::SIMPLE) . '}}, {{address:office_key}}, {{setting:key}}, {{plan:slug}}, {{embed:section_id}}.',
                422,
                ['unknown_placeholders' => $bad]
            );
        }
    }

    public static function render(string $html, ?int $pageId = null): string
    {
        if (!str_contains($html, '{{')) {
            return $html;
        }

        return preg_replace_callback(self::PATTERN, function ($m) use ($pageId) {
            $name = strtolower($m[1]);
            $arg = $m[2] ?? '';
            $e = static fn ($v) => htmlspecialchars((string) $v, ENT_QUOTES | ENT_HTML5, 'UTF-8');
            $s = SiteSettings::all();

            switch ($name) {
                case 'phone':
                case 'email':
                case 'whatsapp':
                case 'toll_free':
                case 'hours':
                    return $e($s[$name] ?? '');
                case 'phone_raw':
                    return $e(preg_replace('/[^0-9+]/', '', (string) ($s['phone'] ?? '')));
                case 'wa_link':
                    return $e('https://wa.me/' . preg_replace('/\D/', '', (string) ($s['whatsapp'] ?? '')));
                case 'setting':
                    return $e($s[$arg] ?? '');
                case 'address':
                    $o = Office::query()->where('is_deleted', 0)->where('key', $arg)->first();

                    return $o ? $e($o->addressLine()) : '';
                case 'plan':
                    $p = PricingPlan::query()->where('is_deleted', 0)->where('is_published', 1)->where('slug', $arg)->first();

                    return $p ? $e($p->priceText()) : $e('Ask for a quote');
                case 'embed':
                    $q = PageSection::query()->active()->where('type', 'component')->whereKey((int) $arg);
                    if ($pageId) {
                        $q->where('page_id', $pageId);
                    }
                    $section = $q->first();

                    return $section ? Components::render((string) $section->component, (array) ($section->attrs ?? []), $pageId) : '';
            }

            return $m[0];
        }, $html);
    }
}
