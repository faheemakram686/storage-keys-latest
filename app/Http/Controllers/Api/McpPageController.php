<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

/**
 * MCP CRUD for DB-driven CMS pages.
 * Does not replace static Blade marketing pages — those stay on existing routes.
 * API lives under /api/mcp/cms-pages so GET /api/mcp/pages (static get_pages) is untouched.
 */
class McpPageController extends Controller
{
    public function index(Request $request)
    {
        $limit = min(50, max(1, (int) $request->query('limit', 20)));

        $pages = Page::query()
            ->active()
            ->orderByDesc('id')
            ->limit($limit)
            ->get();

        $items = $pages->map(fn (Page $page) => $this->formatPage($page, false))->values();

        return response()->json([
            'success' => true,
            'count' => $items->count(),
            'pages' => $items,
        ]);
    }

    public function show(Request $request, $idOrSlug)
    {
        $page = $this->findActivePage($idOrSlug);
        if (!$page) {
            return response()->json(['success' => false, 'message' => 'Page not found.'], 404);
        }

        return response()->json([
            'success' => true,
            'page' => $this->formatPage($page, true),
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'status' => 'nullable|in:0,1',
            'slug' => 'nullable|string|max:255',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:512',
            'hide_banner' => 'nullable',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors(),
            ], 422);
        }

        $title = trim($request->input('title'));
        $slugBase = $request->filled('slug')
            ? Str::slug($request->input('slug'), '-')
            : Str::slug($title, '-');

        if ($slugBase === '') {
            $slugBase = 'page-' . Carbon::now()->format('YmdHis');
        }

        if ($this->isReservedSlug($slugBase)) {
            return response()->json([
                'success' => false,
                'message' => 'Slug is reserved by an existing static site route. Choose a different slug so current pages/URLs stay unchanged.',
                'reserved_slug' => $slugBase,
            ], 422);
        }

        $slug = $this->uniqueSlug($slugBase);
        $status = $request->has('status') ? (int) $request->input('status') : 0;

        $page = new Page();
        $page->title = $title;
        $page->slug = $slug;
        $page->content = $request->input('content');
        $page->status = $status;
        $page->is_deleted = 0;
        $page->meta_title = $request->input('meta_title');
        $page->meta_description = $request->input('meta_description');
        if ($request->exists('hide_banner')) {
            $page->hide_banner = $this->parseBoolish($request->input('hide_banner'));
        } else {
            // Auto-flag when content already ships a full hero (service-page style).
            $page->hide_banner = (new Page(['content' => $page->content]))->contentStartsWithHero();
        }
        $page->save();

        return response()->json([
            'success' => true,
            'message' => 'Page created successfully',
            'page' => $this->formatPage($page->fresh(), true),
        ], 201);
    }

    public function update(Request $request, $idOrSlug)
    {
        $page = $this->findActivePage($idOrSlug);
        if (!$page) {
            return response()->json(['success' => false, 'message' => 'Page not found.'], 404);
        }

        $validator = Validator::make($request->all(), [
            'title' => 'sometimes|required|string|max:255',
            'content' => 'sometimes|required|string',
            'status' => 'nullable|in:0,1',
            'slug' => 'nullable|string|max:255',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:512',
            'hide_banner' => 'nullable',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors(),
            ], 422);
        }

        if ($request->filled('title')) {
            $page->title = trim($request->input('title'));
        }
        if ($request->has('content')) {
            $page->content = $request->input('content');
        }
        if ($request->has('status') && in_array((string) $request->input('status'), ['0', '1'], true)) {
            $page->status = (int) $request->input('status');
        }
        if ($request->filled('slug')) {
            $slugBase = Str::slug($request->input('slug'), '-');
            if ($slugBase === '') {
                $slugBase = 'page-' . Carbon::now()->format('YmdHis');
            }
            if ($this->isReservedSlug($slugBase)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Slug is reserved by an existing static site route.',
                    'reserved_slug' => $slugBase,
                ], 422);
            }
            $page->slug = $this->uniqueSlug($slugBase, (int) $page->id);
        }
        if ($request->has('meta_title')) {
            $page->meta_title = $request->input('meta_title') ?: null;
        }
        if ($request->has('meta_description')) {
            $page->meta_description = $request->input('meta_description') ?: null;
        }
        if ($request->exists('hide_banner')) {
            $page->hide_banner = $this->parseBoolish($request->input('hide_banner'));
        } elseif ($request->has('content') && !$page->hide_banner) {
            // If still using default banner mode, auto-enable when new content has a hero.
            if ($page->contentStartsWithHero()) {
                $page->hide_banner = true;
            }
        }

        $page->save();

        return response()->json([
            'success' => true,
            'message' => 'Page updated successfully',
            'page' => $this->formatPage($page->fresh(), true),
        ]);
    }

    public function destroy(Request $request, $idOrSlug)
    {
        $confirm = filter_var($request->input('confirm', false), FILTER_VALIDATE_BOOLEAN);
        if (!$confirm && $request->input('confirm') !== 1 && $request->input('confirm') !== '1') {
            return response()->json([
                'success' => false,
                'message' => 'Soft delete requires confirm=true.',
            ], 422);
        }

        $page = $this->findActivePage($idOrSlug);
        if (!$page) {
            return response()->json(['success' => false, 'message' => 'Page not found.'], 404);
        }

        $page->is_deleted = 1;
        $page->save();

        return response()->json([
            'success' => true,
            'message' => 'Page soft-deleted.',
            'page' => $this->formatPage($page->fresh(), false),
        ]);
    }

    public function formatPage(Page $page, bool $includeContent): array
    {
        $rawStatus = (int) $page->getRawOriginal('status');

        $data = [
            'id' => $page->id,
            'title' => $page->title,
            'slug' => $page->slug,
            'status' => $rawStatus,
            'status_label' => $rawStatus === 1 ? 'Published' : 'Draft',
            'url' => url('/' . $page->slug),
            'meta_title' => $page->meta_title,
            'meta_description' => $page->meta_description,
            'hide_banner' => (bool) $page->hide_banner,
            'banner_hidden' => $page->shouldHideBanner(),
            'full_bleed' => $page->shouldHideBanner(),
            'created_at' => optional($page->created_at)->toDateTimeString(),
            'updated_at' => optional($page->updated_at)->toDateTimeString(),
        ];

        if ($includeContent) {
            $data['content'] = $page->content;
        }

        return $data;
    }

    /**
     * Accept true/false, 1/0, "true"/"false", "yes"/"no" from MCP clients.
     */
    private function parseBoolish($value): bool
    {
        if (is_bool($value)) {
            return $value;
        }
        if (is_int($value) || is_float($value)) {
            return (int) $value === 1;
        }

        $parsed = filter_var($value, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
        if ($parsed !== null) {
            return $parsed;
        }

        return in_array(strtolower(trim((string) $value)), ['1', 'true', 'yes', 'on'], true);
    }

    private function findActivePage($idOrSlug): ?Page
    {
        $q = Page::query()->active();
        if (is_numeric($idOrSlug)) {
            return $q->where('id', (int) $idOrSlug)->first();
        }

        return $q->where('slug', (string) $idOrSlug)->first();
    }

    private function uniqueSlug(string $base, ?int $ignoreId = null): string
    {
        $slug = $base;
        $i = 2;
        while (
            Page::query()
                ->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))
                ->where('slug', $slug)
                ->exists()
        ) {
            $slug = $base . '-' . $i;
            $i++;
        }

        return $slug;
    }

    /**
     * Slugs owned by existing static Blade / app routes — never override these.
     */
    public function isReservedSlug(string $slug): bool
    {
        $slug = strtolower(trim($slug, '/'));

        return in_array($slug, $this->reservedSlugs(), true);
    }

    /**
     * @return list<string>
     */
    public function reservedSlugs(): array
    {
        return [
            '',
            'admin',
            'api',
            'login',
            'logout',
            'register',
            'password',
            'storage',
            'sanctum',
            'horizon',
            'telescope',
            'sitemap.xml',
            'robots.txt',
            'notify',
            'storage-options',
            'shop',
            'business-storage',
            'warehouse-storage',
            'personal-storage',
            'furniture-storage',
            'box-storage',
            'appliance-storage',
            'residential-storage',
            'climate-controlled-storage',
            'moving-services',
            'luggage-storage',
            'car-storage',
            'short-term-storage',
            'vehicle-storage',
            'long-term-storage',
            'product-details',
            'product-detail',
            'booking',
            'blogs',
            'blog-details',
            'about-us',
            'contact-us',
            'privacy-policy',
            'security-policy',
            'support-policy',
            'cookie-policy',
            'terms-of-service',
            'frequently-asked-questions',
            'thank-you',
            'cart',
            'checkout',
            'test',
            'inquiry',
            'save-lead',
            'get-cities',
            'get-locations',
            'get-warehouse',
            'get-storageunit',
            'country-wise',
            'send',
            'apply-coupon',
            'clear',
            'remove',
            'update-cart',
            'save-order',
            'redirectPaymentRef',
            'sign-contract',
            'upload-estimate-documents',
        ];
    }
}
