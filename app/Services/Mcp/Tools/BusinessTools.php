<?php

namespace App\Services\Mcp\Tools;

use App\Models\Faq;
use App\Models\Inquiry;
use App\Models\Office;
use App\Models\PricingPlan;
use App\Models\Testimonial;
use App\Services\Mcp\FaqSchema;
use App\Services\Mcp\HtmlSanitizer;
use App\Services\Mcp\McpException;
use App\Services\Mcp\Placeholders;
use App\Services\Mcp\SiteSettings;
use App\Services\Mcp\Versioning;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

/**
 * Phase 8: settings and placeholders, offices, FAQs, testimonials, pricing plans, enquiries.
 * Only real data from the owner: these tools never invent addresses, reviews or prices.
 */
class BusinessTools extends Toolset
{
    public function definitions(): array
    {
        $id = ['type' => 'integer'];
        $expected = ['type' => 'string', 'description' => 'updated_at from the list tool'];
        $published = ['type' => 'boolean', 'description' => 'Default false (draft). Only true when the owner asks to publish.'];

        return [
            $this->tool('get_settings', 'Site settings used by placeholders in DB pages and blog posts ({{phone}}, {{email}}, {{whatsapp}}, {{wa_link}}, {{toll_free}}, {{hours}}, {{setting:key}}), and the full placeholder list.'),
            $this->tool('update_settings', 'Change one setting: phone, whatsapp (digits with country code), email, toll_free, hours, or any stat_<name> / custom_<name>. An empty value resets it. Coded pages keep their own text; placeholders in DB pages and posts change at once.', [
                'key' => ['type' => 'string'],
                'value' => ['type' => 'string'],
            ], ['key', 'value']),

            $this->tool('list_offices', 'StorageKeys facilities: name, address, map link, hours, phone, coordinates. The address, map link and coordinates also feed the location pages, the business schema and {{address:key}}.'),
            $this->tool('create_office', 'Add a facility (real details from the owner only). key is used in {{address:key}}.', [
                'key' => ['type' => 'string'], 'name' => ['type' => 'string'], 'street' => ['type' => 'string'], 'locality' => ['type' => 'string'], 'region' => ['type' => 'string'],
                'map_link' => ['type' => 'string'], 'hours' => ['type' => 'string'], 'phone' => ['type' => 'string'], 'email' => ['type' => 'string'],
                'lat' => ['type' => 'number'], 'lng' => ['type' => 'number'],
            ], ['key', 'name', 'street', 'locality', 'region']),
            $this->tool('update_office', 'Change a facility\'s details. For dubai and sharjah this changes the address and map link on the location pages and in the business schema. Requires expected_updated_at.', [
                'id' => $id, 'expected_updated_at' => $expected, 'name' => ['type' => 'string'], 'street' => ['type' => 'string'], 'locality' => ['type' => 'string'], 'region' => ['type' => 'string'],
                'map_link' => ['type' => 'string'], 'hours' => ['type' => 'string'], 'phone' => ['type' => 'string'], 'email' => ['type' => 'string'],
                'lat' => ['type' => 'number'], 'lng' => ['type' => 'number'],
            ], ['id', 'expected_updated_at']),
            $this->tool('delete_office', 'Move a facility to the trash. For dubai and sharjah the location pages fall back to the details in the code.', ['id' => $id], ['id']),

            $this->tool('list_faqs', 'FAQs (question, answer, page_slugs, order, published), optionally for one page.', ['page_slug' => ['type' => 'string']]),
            $this->tool('create_faq', 'Add an FAQ to one or more pages. It shows where the page has a faq_list component section, and that page\'s FAQPage schema is updated.', [
                'question' => ['type' => 'string'], 'answer' => ['type' => 'string', 'description' => 'Text or simple HTML'],
                'page_slugs' => ['type' => 'array', 'items' => ['type' => 'string']], 'position' => ['type' => 'integer'], 'published' => $published,
            ], ['question', 'answer', 'page_slugs']),
            $this->tool('update_faq', 'Edit an FAQ (text, pages, order, published). Updates the FAQPage schema of every affected page. Requires expected_updated_at.', [
                'id' => $id, 'expected_updated_at' => $expected, 'question' => ['type' => 'string'], 'answer' => ['type' => 'string'],
                'page_slugs' => ['type' => 'array', 'items' => ['type' => 'string']], 'position' => ['type' => 'integer'], 'published' => ['type' => 'boolean'],
            ], ['id', 'expected_updated_at']),
            $this->tool('delete_faq', 'Move an FAQ to the trash and update the FAQPage schema of its pages.', ['id' => $id], ['id']),

            $this->tool('list_testimonials', 'Customer reviews stored for the testimonials component.'),
            $this->tool('create_testimonial', 'Add a real customer review the owner supplied (never write one). source says where it came from (Google, Facebook, email). Shown by the testimonials component; never output as Review schema.', [
                'name' => ['type' => 'string'], 'text' => ['type' => 'string'], 'source' => ['type' => 'string'], 'source_url' => ['type' => 'string'],
                'date' => ['type' => 'string', 'description' => 'Review date, YYYY-MM-DD'], 'published' => $published,
            ], ['name', 'text', 'source']),
            $this->tool('update_testimonial', 'Edit a testimonial. Requires expected_updated_at.', [
                'id' => $id, 'expected_updated_at' => $expected, 'name' => ['type' => 'string'], 'text' => ['type' => 'string'], 'source' => ['type' => 'string'],
                'source_url' => ['type' => 'string'], 'date' => ['type' => 'string'], 'published' => ['type' => 'boolean'],
            ], ['id', 'expected_updated_at']),
            $this->tool('delete_testimonial', 'Move a testimonial to the trash.', ['id' => $id], ['id']),

            $this->tool('list_pricing_plans', 'Storage unit prices for {{plan:slug}} and the pricing_table component.'),
            $this->tool('create_pricing_plan', 'Add a unit size and price the owner gave you. Leave price out when there is none ("Ask for a quote"); never estimate one.', [
                'slug' => ['type' => 'string'], 'name' => ['type' => 'string'], 'unit_size' => ['type' => 'string', 'description' => 'e.g. "5 x 5 ft (25 sq ft)"'],
                'price' => ['type' => 'number'], 'currency' => ['type' => 'string', 'description' => 'Default AED'], 'period' => ['type' => 'string', 'enum' => ['month', 'week', 'day', 'year']],
                'features' => ['type' => 'array', 'items' => ['type' => 'string']], 'position' => ['type' => 'integer'], 'published' => $published,
            ], ['name']),
            $this->tool('update_pricing_plan', 'Edit a pricing plan. Pass price as null to remove it. Requires expected_updated_at.', [
                'id' => $id, 'expected_updated_at' => $expected, 'name' => ['type' => 'string'], 'unit_size' => ['type' => 'string'], 'price' => ['type' => 'number'],
                'currency' => ['type' => 'string'], 'period' => ['type' => 'string', 'enum' => ['month', 'week', 'day', 'year']],
                'features' => ['type' => 'array', 'items' => ['type' => 'string']], 'position' => ['type' => 'integer'], 'published' => ['type' => 'boolean'],
            ], ['id', 'expected_updated_at']),
            $this->tool('delete_pricing_plan', 'Move a pricing plan to the trash ({{plan:slug}} then shows "Ask for a quote").', ['id' => $id], ['id']),

            $this->tool('list_enquiries', 'Quote requests sent through the website forms (read-only). Dates in Asia/Dubai.', [
                'limit' => ['type' => 'integer', 'minimum' => 1, 'maximum' => 200],
                'from' => ['type' => 'string', 'description' => 'YYYY-MM-DD'],
                'to' => ['type' => 'string', 'description' => 'YYYY-MM-DD'],
            ]),
        ];
    }

    public function call(string $name, array $a): array
    {
        switch ($name) {
            case 'get_settings':
                return $this->getSettings();
            case 'update_settings':
                return ['message' => 'Setting saved.'] + SiteSettings::set((string) $this->str($a, 'key', true), array_key_exists('value', $a) && $a['value'] !== null ? (string) $a['value'] : null);
            case 'list_offices':
                return $this->listOf(Office::query()->orderBy('id')->get(), 'offices', fn (Office $o) => $o->only(['id', 'key', 'name', 'street', 'locality', 'region', 'map_link', 'hours', 'phone', 'email', 'lat', 'lng']) + ['address' => $o->addressLine(), 'placeholder' => '{{address:' . $o->key . '}}']);
            case 'create_office':
                return $this->saveOffice(new Office(), $a, true);
            case 'update_office':
                return $this->saveOffice($this->find(Office::class, $a, 'Office'), $a, false);
            case 'delete_office':
                return $this->trash($this->find(Office::class, $a, 'Office'), 'office');
            case 'list_faqs':
                return $this->listFaqs($a);
            case 'create_faq':
                return $this->saveFaq(new Faq(), $a, true);
            case 'update_faq':
                return $this->saveFaq($this->find(Faq::class, $a, 'FAQ'), $a, false);
            case 'delete_faq':
                $faq = $this->find(Faq::class, $a, 'FAQ');

                return $this->trash($faq, 'faq') + ['schema' => FaqSchema::sync((array) $faq->page_slugs)];
            case 'list_testimonials':
                return $this->listOf(Testimonial::query()->where('is_deleted', 0)->orderByDesc('id')->get(), 'testimonials', fn ($t) => $this->presentTestimonial($t));
            case 'create_testimonial':
                return $this->saveTestimonial(new Testimonial(), $a, true);
            case 'update_testimonial':
                return $this->saveTestimonial($this->find(Testimonial::class, $a, 'Testimonial'), $a, false);
            case 'delete_testimonial':
                return $this->trash($this->find(Testimonial::class, $a, 'Testimonial'), 'testimonial');
            case 'list_pricing_plans':
                return $this->listOf(PricingPlan::query()->where('is_deleted', 0)->orderBy('position')->orderBy('id')->get(), 'plans', fn ($p) => $this->presentPlan($p));
            case 'create_pricing_plan':
                return $this->savePlan(new PricingPlan(), $a, true);
            case 'update_pricing_plan':
                return $this->savePlan($this->find(PricingPlan::class, $a, 'Pricing plan'), $a, false);
            case 'delete_pricing_plan':
                return $this->trash($this->find(PricingPlan::class, $a, 'Pricing plan'), 'pricing_plan');
            case 'list_enquiries':
                return $this->enquiries($a);
        }

        throw new McpException('Unknown tool: ' . $name, 404);
    }

    // ---------------------------------------------------------------- shared

    private function find(string $class, array $a, string $label): Model
    {
        $m = $class::query()->where('is_deleted', 0)->find($this->int($a, 'id', null, true));
        if (!$m) {
            throw McpException::notFound($label . ' ' . ($a['id'] ?? ''));
        }

        return $m;
    }

    private function listOf($rows, string $key, callable $map): array
    {
        $items = $rows->filter(fn ($r) => (int) $r->is_deleted === 0)->map(fn ($r) => $map($r) + ['updated_at' => Versioning::stamp($r)])->values();

        return ['count' => $items->count(), $key => $items];
    }

    private function trash(Model $m, string $type): array
    {
        $m->setAttribute('is_deleted', 1);
        $m->save();

        return ['message' => ucfirst(str_replace('_', ' ', $type)) . ' moved to the trash. restore_item with type=' . $type . ' and id=' . $m->getKey() . ' brings it back.', 'id' => $m->getKey()];
    }

    private function checkVersion(Model $m, array $a, bool $creating, string $label): void
    {
        if (!$creating) {
            Versioning::assertCurrent($m, $a['expected_updated_at'] ?? null, $label);
        }
    }

    private function text(array $a, string $key, int $max, bool $required = false): ?string
    {
        if (!array_key_exists($key, $a) || $a[$key] === null) {
            if ($required) {
                throw new McpException($key . ' is required.', 422);
            }

            return null;
        }
        $value = trim(strip_tags((string) $a[$key]));
        if ($required && $value === '') {
            throw new McpException($key . ' is required.', 422);
        }
        if (mb_strlen($value) > $max) {
            throw new McpException($key . ' is longer than ' . $max . ' characters.', 422);
        }

        return $value;
    }

    // ---------------------------------------------------------------- settings

    private function getSettings(): array
    {
        $values = SiteSettings::all();
        $settings = [];
        foreach ($values as $key => $value) {
            $settings[] = [
                'key' => $key,
                'value' => $value,
                'default' => SiteSettings::DEFAULTS[$key] ?? null,
                'placeholder' => isset(SiteSettings::DEFAULTS[$key]) ? '{{' . $key . '}}' : '{{setting:' . $key . '}}',
                'description' => SiteSettings::DESCRIPTIONS[$key] ?? 'Custom value',
            ];
        }

        return [
            'settings' => $settings,
            'placeholders' => [
                '{{phone}}', '{{phone_raw}}', '{{email}}', '{{whatsapp}}', '{{wa_link}}', '{{toll_free}}', '{{hours}}',
                '{{address:<office key>}}', '{{setting:<key>}}', '{{plan:<plan slug>}}', '{{embed:<component section id>}}',
            ],
            'note' => 'Placeholders work in DB pages, sections and blog posts. Coded pages show the values written in their templates.',
        ];
    }

    // ---------------------------------------------------------------- offices

    private function saveOffice(Office $o, array $a, bool $creating): array
    {
        $this->checkVersion($o, $a, $creating, 'Office');
        if ($creating) {
            $key = Str::slug((string) $this->str($a, 'key', true), '_');
            if ($key === '' || Office::query()->where('key', $key)->exists()) {
                throw new McpException('key must be new and made of letters, digits or _.', 422);
            }
            $o->key = $key;
        }
        foreach (['name' => 150, 'street' => 255, 'locality' => 150, 'region' => 100] as $f => $max) {
            if (($v = $this->text($a, $f, $max, $creating)) !== null) {
                if ($v === '') {
                    throw new McpException($f . ' cannot be empty.', 422);
                }
                $o->setAttribute($f, $v);
            }
        }
        foreach (['hours' => 150, 'phone' => 40, 'email' => 150] as $f => $max) {
            if (($v = $this->text($a, $f, $max)) !== null) {
                $o->setAttribute($f, $v === '' ? null : $v);
            }
        }
        if (($link = $this->text($a, 'map_link', 500)) !== null) {
            if ($link !== '' && !preg_match('#^https://#i', $link)) {
                throw new McpException('map_link must be an https:// URL.', 422);
            }
            $o->map_link = $link ?: null;
        }
        if ($o->email && !filter_var($o->email, FILTER_VALIDATE_EMAIL)) {
            throw new McpException('email is not valid.', 422);
        }
        foreach (['lat' => 90, 'lng' => 180] as $f => $limit) {
            if (array_key_exists($f, $a)) {
                if ($a[$f] !== null && (!is_numeric($a[$f]) || abs((float) $a[$f]) > $limit)) {
                    throw new McpException($f . ' is not a valid coordinate.', 422);
                }
                $o->setAttribute($f, $a[$f] === null ? null : (float) $a[$f]);
            }
        }
        if (!$creating && !$o->isDirty()) {
            throw new McpException('Nothing to change.', 422);
        }
        $o->save();

        $note = in_array($o->key, \App\Support\LocationPages::CITIES, true)
            ? 'The /' . \App\Support\LocationPages::cityPageSlug($o->key) . ' page, its service pages and the business schema now use these details.'
            : 'Use {{address:' . $o->key . '}} in DB pages and posts. No location page exists for this office.';

        return ['message' => 'Office saved. ' . $note, 'office' => $o->fresh()->only(['id', 'key', 'name', 'street', 'locality', 'region', 'map_link', 'hours', 'phone', 'email', 'lat', 'lng']) + ['updated_at' => Versioning::stamp($o->fresh())]];
    }

    // ---------------------------------------------------------------- FAQs

    private function presentFaq(Faq $f): array
    {
        return ['id' => $f->id, 'question' => $f->question, 'answer' => $f->answer, 'page_slugs' => (array) $f->page_slugs, 'position' => (int) $f->position, 'published' => (bool) $f->is_published];
    }

    private function listFaqs(array $a): array
    {
        $q = Faq::query()->where('is_deleted', 0)->orderBy('position')->orderBy('id');
        if ($slug = $this->str($a, 'page_slug')) {
            $q->whereJsonContains('page_slugs', trim($slug, '/'));
        }

        return $this->listOf($q->get(), 'faqs', fn ($f) => $this->presentFaq($f));
    }

    private function saveFaq(Faq $f, array $a, bool $creating): array
    {
        $this->checkVersion($f, $a, $creating, 'FAQ');
        $before = (array) ($f->page_slugs ?? []);

        if (($q = $this->text($a, 'question', 500, $creating)) !== null) {
            $f->question = $q;
        }
        if (array_key_exists('answer', $a) || $creating) {
            $answer = HtmlSanitizer::clean(trim((string) $this->str($a, 'answer', true)))['html'];
            Placeholders::assertKnown($answer);
            $f->answer = $answer;
        }
        if (array_key_exists('page_slugs', $a) || $creating) {
            $slugs = array_values(array_unique(array_filter(array_map(fn ($s) => trim((string) $s, '/ '), $this->arr($a, 'page_slugs', true)))));
            if ($slugs === []) {
                throw new McpException('page_slugs needs at least one page slug.', 422);
            }
            $f->page_slugs = $slugs;
        }
        if (array_key_exists('position', $a)) {
            $f->position = $this->int($a, 'position', 0, false, 0);
        }
        if (array_key_exists('published', $a) || $creating) {
            $f->is_published = $this->bool($a, 'published', false);
        }
        if (!$creating && !$f->isDirty()) {
            throw new McpException('Nothing to change.', 422);
        }
        $f->save();

        return [
            'message' => $creating ? 'FAQ added' . ($f->is_published ? '.' : ' as a draft (published=false); it shows once published.') : 'FAQ updated.',
            'faq' => $this->presentFaq($f) + ['updated_at' => Versioning::stamp($f)],
            'schema' => FaqSchema::sync(array_merge($before, (array) $f->page_slugs)),
        ];
    }

    // ---------------------------------------------------------------- testimonials

    private function presentTestimonial(Testimonial $t): array
    {
        return ['id' => $t->id, 'name' => $t->name, 'text' => $t->text, 'source' => $t->source, 'source_url' => $t->source_url, 'date' => optional($t->review_date)->format('Y-m-d'), 'published' => (bool) $t->is_published];
    }

    private function saveTestimonial(Testimonial $t, array $a, bool $creating): array
    {
        $this->checkVersion($t, $a, $creating, 'Testimonial');
        foreach (['name' => 150, 'text' => 5000, 'source' => 100] as $f => $max) {
            if (($v = $this->text($a, $f, $max, $creating)) !== null) {
                if ($v === '') {
                    throw new McpException($f . ' cannot be empty.', 422);
                }
                $t->setAttribute($f, $v);
            }
        }
        if (($url = $this->text($a, 'source_url', 500)) !== null) {
            if ($url !== '' && !preg_match('#^https?://#i', $url)) {
                throw new McpException('source_url must be a link.', 422);
            }
            $t->source_url = $url ?: null;
        }
        if (array_key_exists('date', $a)) {
            try {
                $t->review_date = $a['date'] ? Carbon::parse((string) $a['date'])->startOfDay() : null;
            } catch (\Throwable $e) {
                throw new McpException('date must be YYYY-MM-DD.', 422);
            }
            if ($t->review_date && $t->review_date->isFuture()) {
                throw new McpException('date cannot be in the future.', 422);
            }
        }
        if (array_key_exists('published', $a) || $creating) {
            $t->is_published = $this->bool($a, 'published', false);
        }
        if (!$creating && !$t->isDirty()) {
            throw new McpException('Nothing to change.', 422);
        }
        $t->save();

        return ['message' => 'Testimonial saved' . ($t->is_published ? '.' : ' as a draft.'), 'testimonial' => $this->presentTestimonial($t) + ['updated_at' => Versioning::stamp($t)]];
    }

    // ---------------------------------------------------------------- pricing

    private function presentPlan(PricingPlan $p): array
    {
        return [
            'id' => $p->id, 'slug' => $p->slug, 'name' => $p->name, 'unit_size' => $p->unit_size,
            'price' => $p->price === null ? null : (float) $p->price, 'currency' => $p->currency, 'period' => $p->period,
            'price_text' => $p->priceText(), 'features' => (array) ($p->features ?? []), 'position' => (int) $p->position,
            'published' => (bool) $p->is_published, 'placeholder' => '{{plan:' . $p->slug . '}}',
        ];
    }

    private function savePlan(PricingPlan $p, array $a, bool $creating): array
    {
        $this->checkVersion($p, $a, $creating, 'Pricing plan');
        if (($name = $this->text($a, 'name', 150, $creating)) !== null) {
            $p->name = $name;
        }
        if ($creating) {
            $slug = Str::slug((string) ($this->str($a, 'slug') ?: $p->name));
            if ($slug === '' || PricingPlan::query()->where('slug', $slug)->exists()) {
                throw new McpException('slug "' . $slug . '" is taken or empty; pass another slug.', 422);
            }
            $p->slug = $slug;
        }
        if (($size = $this->text($a, 'unit_size', 100)) !== null) {
            $p->unit_size = $size ?: null;
        }
        if (array_key_exists('price', $a)) {
            if ($a['price'] !== null && $a['price'] !== '' && (!is_numeric($a['price']) || (float) $a['price'] < 0)) {
                throw new McpException('price must be a positive number, or null for "Ask for a quote".', 422);
            }
            $p->price = $a['price'] === null || $a['price'] === '' ? null : round((float) $a['price'], 2);
        }
        if (($cur = $this->text($a, 'currency', 3)) !== null) {
            if (!preg_match('/^[A-Z]{3}$/', strtoupper($cur))) {
                throw new McpException('currency must be a 3-letter code such as AED.', 422);
            }
            $p->currency = strtoupper($cur);
        }
        if (($period = $this->str($a, 'period')) !== null) {
            if (!in_array($period, ['month', 'week', 'day', 'year'], true)) {
                throw new McpException('period must be month, week, day or year.', 422);
            }
            $p->period = $period;
        }
        if (array_key_exists('features', $a)) {
            $p->features = array_values(array_filter(array_map(fn ($f) => mb_substr(trim(strip_tags((string) $f)), 0, 200), $this->arr($a, 'features'))));
        }
        if (array_key_exists('position', $a)) {
            $p->position = $this->int($a, 'position', 0, false, 0);
        }
        if (array_key_exists('published', $a) || $creating) {
            $p->is_published = $this->bool($a, 'published', false);
        }
        if (!$creating && !$p->isDirty()) {
            throw new McpException('Nothing to change.', 422);
        }
        $p->save();

        return ['message' => 'Pricing plan saved' . ($p->is_published ? '.' : ' as a draft (not shown until published).'), 'plan' => $this->presentPlan($p) + ['updated_at' => Versioning::stamp($p)]];
    }

    // ---------------------------------------------------------------- enquiries

    private function enquiries(array $a): array
    {
        $tz = 'Asia/Dubai';
        $q = Inquiry::query()->orderByDesc('id');
        try {
            if ($from = $this->str($a, 'from')) {
                $q->where('created_at', '>=', Carbon::parse($from, $tz)->startOfDay()->utc());
            }
            if ($to = $this->str($a, 'to')) {
                $q->where('created_at', '<=', Carbon::parse($to, $tz)->endOfDay()->utc());
            }
        } catch (\Throwable $e) {
            throw new McpException('from and to must be dates (YYYY-MM-DD).', 422);
        }
        $rows = $q->limit($this->int($a, 'limit', 50, false, 1, 200))->get();

        return [
            'count' => $rows->count(),
            'enquiries' => $rows->map(fn (Inquiry $i) => [
                'id' => $i->id,
                'received' => optional($i->created_at)->copy()?->setTimezone($tz)->format('Y-m-d H:i'),
                'name' => $i->name,
                'email' => $i->email,
                'phone' => $i->phone,
                'storage_type' => $i->storage_type,
                'message' => $i->message,
            ])->values(),
            'note' => 'Read-only. These are customer contact details: use them only to follow up the enquiry.',
        ];
    }
}
