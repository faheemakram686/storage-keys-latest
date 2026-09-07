<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

/**
 * Read-only site helpers + media listing/upload for MCP (Phase 1).
 * Does not change frontend pages.
 */
class McpSiteController extends Controller
{
    public function siteInfo()
    {
        $canonical = rtrim((string) config('app.canonical_url', config('app.url')), '/');
        if (str_starts_with($canonical, 'http://')) {
            $canonical = 'https://' . substr($canonical, 7);
        }

        return response()->json([
            'success' => true,
            'site' => [
                'name' => config('app.name', 'StorageKeys'),
                'base_url' => url('/'),
                'canonical_url' => $canonical !== '' ? $canonical : url('/'),
                'timezone' => config('app.timezone', 'UTC'),
                'locale' => config('app.locale', 'en'),
                'blog_index_url' => url('/blogs'),
                'blog_permalink_pattern' => url('/blogs/{slug}'),
                'sitemap_url' => url('/sitemap.xml'),
                'robots_url' => url('/robots.txt'),
                'mcp_api_base' => url('/api/mcp'),
            ],
        ]);
    }

    public function pages()
    {
        $paths = $this->staticPaths();

        $pages = array_map(function (string $path) {
            return [
                'path' => $path,
                'url' => url($path === '/' ? '/' : ltrim($path, '/')),
                'type' => 'static',
            ];
        }, $paths);

        return response()->json([
            'success' => true,
            'count' => count($pages),
            'pages' => $pages,
        ]);
    }

    public function sitemap()
    {
        $paths = $this->staticPaths();
        $entries = [];

        foreach ($paths as $path) {
            $entries[] = [
                'loc' => url($path === '/' ? '/' : ltrim($path, '/')),
                'type' => 'static',
            ];
        }

        $blogs = \App\Models\Blog::query()
            ->where('is_deleted', 0)
            ->whereNotNull('slug')
            ->where('slug', '!=', '')
            ->orderByDesc('updated_at')
            ->get(['slug', 'updated_at', 'created_at']);

        foreach ($blogs as $blog) {
            $entries[] = [
                'loc' => url('/blogs/' . $blog->slug),
                'type' => 'blog',
                'slug' => $blog->slug,
                'lastmod' => optional($blog->updated_at ?? $blog->created_at)->toAtomString(),
            ];
        }

        return response()->json([
            'success' => true,
            'sitemap_xml_url' => url('/sitemap.xml'),
            'count' => count($entries),
            'entries' => $entries,
        ]);
    }

    public function listMedia(Request $request)
    {
        $limit = min(100, max(1, (int) $request->query('limit', 30)));
        $disk = Storage::disk('public');
        $dir = 'uploads/blog-images';

        if (!$disk->exists($dir)) {
            return response()->json([
                'success' => true,
                'count' => 0,
                'media' => [],
            ]);
        }

        $files = collect($disk->files($dir))
            ->filter(function ($path) {
                $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));

                return in_array($ext, ['jpg', 'jpeg', 'png', 'webp', 'gif'], true);
            })
            ->sortByDesc(function ($path) use ($disk) {
                return $disk->lastModified($path);
            })
            ->take($limit)
            ->values();

        $media = $files->map(function ($path) use ($disk) {
            $name = basename($path);

            return [
                'filename' => $name,
                'path' => $path,
                'url' => asset('storage/' . $path),
                'size' => $disk->size($path),
                'last_modified' => Carbon::createFromTimestamp($disk->lastModified($path))->toDateTimeString(),
            ];
        });

        return response()->json([
            'success' => true,
            'count' => $media->count(),
            'media' => $media,
        ]);
    }

    public function uploadMedia(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'image_url' => 'required|url|max:2048',
            'filename' => 'nullable|string|max:120',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors(),
            ], 422);
        }

        /** @var McpBlogController $blogs */
        $blogs = app(McpBlogController::class);
        $saved = $blogs->storeImageFromUrl($request->input('image_url'));

        if ($saved === 'empty') {
            return response()->json([
                'success' => false,
                'message' => 'Could not download or store image from image_url.',
            ], 422);
        }

        $path = 'uploads/blog-images/' . $saved;

        return response()->json([
            'success' => true,
            'message' => 'Media uploaded',
            'media' => [
                'filename' => $saved,
                'path' => $path,
                'url' => asset('storage/' . $path),
            ],
        ], 201);
    }

    /**
     * @return list<string>
     */
    private function staticPaths(): array
    {
        return [
            '/',
            '/storage-options',
            '/personal-storage',
            '/residential-storage',
            '/furniture-storage',
            '/box-storage',
            '/appliance-storage',
            '/business-storage',
            '/warehouse-storage',
            '/climate-controlled-storage',
            '/moving-services',
            '/luggage-storage',
            '/car-storage',
            '/shop',
            '/booking',
            '/blogs',
            '/about-us',
            '/contact-us',
            '/frequently-asked-questions',
            '/privacy-policy',
            '/security-policy',
            '/support-policy',
            '/cookie-policy',
            '/terms-of-service',
        ];
    }
}
