<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class McpBlogController extends Controller
{
    public function index(Request $request)
    {
        $limit = min(50, max(1, (int) $request->query('limit', 10)));

        $blogs = Blog::query()
            ->where('is_deleted', 0)
            ->orderBy('id', 'DESC')
            ->limit($limit)
            ->get(['id', 'title', 'slug', 'image', 'status', 'created_at', 'updated_at']);

        $items = $blogs->map(function (Blog $blog) {
            return $this->formatBlog($blog, false);
        })->values();

        return response()->json([
            'success' => true,
            'count' => $items->count(),
            'blogs' => $items,
        ]);
    }

    public function show(Request $request, $idOrSlug)
    {
        $blog = $this->findActiveBlog($idOrSlug);

        if (!$blog) {
            return response()->json([
                'success' => false,
                'message' => 'Blog not found.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'blog' => $this->formatBlog($blog, true),
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'status' => 'nullable|in:0,1',
            'image_url' => 'nullable|url|max:2048',
            'image' => 'nullable|string|max:255',
            'slug' => 'nullable|string|max:255',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:512',
            'canonical_url' => 'nullable|url|max:2048',
            'robots' => 'nullable|string|max:64',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors(),
            ], 422);
        }

        $title = trim($request->input('title'));
        $description = $request->input('description');
        // Default draft for MCP uploads — review before publishing
        $status = $request->has('status') ? (int) $request->input('status') : 0;

        $slugBase = $request->filled('slug')
            ? Str::slug($request->input('slug'), '-')
            : Str::slug($title, '-');

        if ($slugBase === '') {
            $slugBase = 'blog-' . Carbon::now()->format('YmdHis');
        }

        $slug = $this->uniqueSlug($slugBase);
        // Image is independent of status — drafts must keep featured images too.
        $imageName = $this->resolveBlogImage(
            $request->input('image_url'),
            $request->input('image')
        );

        $blog = new Blog();
        $blog->title = $title;
        $blog->description = $description;
        $blog->slug = $slug;
        $blog->image = $imageName;
        $blog->status = $status;
        $blog->is_deleted = 0;
        $this->applySeoFields($blog, $request);
        $blog->save();

        return response()->json([
            'success' => true,
            'message' => 'Blog created successfully',
            'blog' => $this->formatBlog($blog->fresh(), true),
        ], 201);
    }

    public function update(Request $request, $idOrSlug)
    {
        $blog = $this->findActiveBlog($idOrSlug);

        if (!$blog) {
            return response()->json([
                'success' => false,
                'message' => 'Blog not found.',
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'title' => 'sometimes|required|string|max:255',
            'description' => 'sometimes|required|string',
            'status' => 'nullable|in:0,1',
            'image_url' => 'nullable|url|max:2048',
            'image' => 'nullable|string|max:255',
            'slug' => 'nullable|string|max:255',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:512',
            'canonical_url' => 'nullable|url|max:2048',
            'robots' => 'nullable|string|max:64',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors(),
            ], 422);
        }

        if ($request->filled('title')) {
            $blog->title = trim($request->input('title'));
        }

        if ($request->has('description')) {
            $blog->description = $request->input('description');
        }

        if ($request->has('status') && in_array((string) $request->input('status'), ['0', '1'], true)) {
            $blog->status = (int) $request->input('status');
        }

        // Only change slug when explicitly provided (never auto-rewrite on title-only updates).
        if ($request->filled('slug')) {
            $slugBase = Str::slug($request->input('slug'), '-');
            if ($slugBase === '') {
                $slugBase = 'blog-' . Carbon::now()->format('YmdHis');
            }
            $blog->slug = $this->uniqueSlug($slugBase, (int) $blog->id);
        }

        if ($request->filled('image_url') || $request->filled('image')) {
            $imageName = $this->resolveBlogImage(
                $request->input('image_url'),
                $request->input('image')
            );
            if ($imageName !== 'empty') {
                $blog->image = $imageName;
            }
        }

        $this->applySeoFields($blog, $request);
        $blog->save();

        return response()->json([
            'success' => true,
            'message' => 'Blog updated successfully',
            'blog' => $this->formatBlog($blog->fresh(), true),
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

        $blog = $this->findActiveBlog($idOrSlug);

        if (!$blog) {
            return response()->json([
                'success' => false,
                'message' => 'Blog not found.',
            ], 404);
        }

        $blog->is_deleted = 1;
        $blog->save();

        return response()->json([
            'success' => true,
            'message' => 'Blog soft-deleted successfully',
            'blog' => [
                'id' => $blog->id,
                'slug' => $blog->slug,
                'is_deleted' => 1,
            ],
        ]);
    }

    public function search(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'q' => 'required|string|min:2|max:200',
            'limit' => 'nullable|integer|min:1|max:50',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors(),
            ], 422);
        }

        $q = trim($request->input('q'));
        $limit = min(50, max(1, (int) $request->input('limit', 10)));
        $like = '%' . str_replace(['%', '_'], ['\\%', '\\_'], $q) . '%';

        $blogs = Blog::query()
            ->where('is_deleted', 0)
            ->where(function ($query) use ($like) {
                $query->where('title', 'like', $like)
                    ->orWhere('description', 'like', $like)
                    ->orWhere('slug', 'like', $like);
            })
            ->orderBy('id', 'DESC')
            ->limit($limit)
            ->get(['id', 'title', 'slug', 'image', 'status', 'created_at', 'updated_at']);

        $items = $blogs->map(function (Blog $blog) {
            return $this->formatBlog($blog, false);
        })->values();

        return response()->json([
            'success' => true,
            'query' => $q,
            'count' => $items->count(),
            'blogs' => $items,
        ]);
    }

    public function bulkUpdate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'ids' => 'required|array|min:1|max:50',
            'ids.*' => 'integer',
            'status' => 'nullable|in:0,1',
            'confirm' => 'nullable',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors(),
            ], 422);
        }

        if (!$request->has('status')) {
            return response()->json([
                'success' => false,
                'message' => 'Provide status (0 or 1) for bulk update.',
            ], 422);
        }

        $confirm = filter_var($request->input('confirm', false), FILTER_VALIDATE_BOOLEAN)
            || $request->input('confirm') === 1
            || $request->input('confirm') === '1';

        if (!$confirm) {
            return response()->json([
                'success' => false,
                'message' => 'bulk_update_posts requires confirm=true.',
            ], 422);
        }

        $ids = array_values(array_unique(array_map('intval', $request->input('ids', []))));
        $status = (int) $request->input('status');

        $updated = Blog::query()
            ->where('is_deleted', 0)
            ->whereIn('id', $ids)
            ->update(['status' => $status, 'updated_at' => Carbon::now()]);

        return response()->json([
            'success' => true,
            'message' => 'Bulk update applied',
            'requested_ids' => $ids,
            'updated_count' => $updated,
            'status' => $status,
        ]);
    }

    private function findActiveBlog($idOrSlug): ?Blog
    {
        $query = Blog::query()->where('is_deleted', 0);

        if (is_numeric($idOrSlug)) {
            return $query->where('id', (int) $idOrSlug)->first();
        }

        return $query->where('slug', (string) $idOrSlug)->first();
    }

    private function applySeoFields(Blog $blog, Request $request): void
    {
        $input = $this->jsonBody($request);
        $limits = [
            'meta_title' => 255,
            'meta_description' => 512,
            'canonical_url' => 2048,
            'robots' => 64,
        ];
        $warnings = [];
        foreach ($limits as $field => $limit) {
            if (!array_key_exists($field, $input)) {
                continue;
            }
            $value = $this->blankToNull($input[$field], $limit, $warnings, $field);
            if ($field === 'canonical_url' && $value !== null && filter_var($value, FILTER_VALIDATE_URL) === false) {
                continue;
            }
            $blog->{$field} = $value;
        }
    }

    /**
     * Request input plus a raw JSON body, for clients that omit Content-Type.
     *
     * @return array<string, mixed>
     */
    private function jsonBody(Request $request): array
    {
        $data = $request->all();
        $raw = $request->getContent();
        if (!is_string($raw) || trim($raw) === '') {
            return is_array($data) ? $data : [];
        }
        $decoded = json_decode($raw, true);
        if (!is_array($decoded)) {
            return is_array($data) ? $data : [];
        }

        return array_merge($decoded, is_array($data) ? $data : []);
    }

    /**
     * @param  array<int, string>  $warnings
     */
    private function blankToNull($value, int $limit, array &$warnings, string $field): ?string
    {
        if ($value === null) {
            return null;
        }
        if (is_array($value) || is_object($value)) {
            $value = json_encode($value, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        }
        $value = trim((string) $value);
        if ($value === '') {
            return null;
        }
        if (mb_strlen($value) > $limit) {
            $value = mb_substr($value, 0, $limit);
            $warnings[] = $field . ' was shortened to ' . $limit . ' characters.';
        }

        return $value;
    }

    private function uniqueSlug(string $base, ?int $ignoreId = null): string
    {
        $slug = $base;
        $i = 1;

        while (
            Blog::query()
                ->where('slug', $slug)
                ->where('is_deleted', 0)
                ->when($ignoreId, function ($q) use ($ignoreId) {
                    $q->where('id', '!=', $ignoreId);
                })
                ->exists()
        ) {
            $slug = $base . '-' . $i;
            $i++;
        }

        return $slug;
    }

    /**
     * Resolve a blog featured image filename.
     * Accepts either an already-uploaded filename (from upload_media) or a URL.
     * Local /storage/uploads/blog-images/* URLs are used as-is (no re-download).
     * Status must never gate this — drafts keep images too.
     */
    public function resolveBlogImage(?string $url, ?string $filename = null): string
    {
        $disk = Storage::disk('public');
        $this->migrateOrphanedBlogImages($disk);

        $filename = trim((string) $filename);
        if ($filename !== '') {
            $filename = basename($filename);
            if ($this->blogImageExists($disk, $filename)) {
                return $filename;
            }
        }

        $url = trim((string) $url);
        if ($url === '') {
            return 'empty';
        }

        // Already on our public storage — attach filename, do not HTTP-fetch ourselves.
        // Avoid #...# regex delimiters here: character class [^/?#] would terminate early.
        $marker = '/storage/uploads/blog-images/';
        $pos = stripos($url, $marker);
        if ($pos !== false) {
            $after = substr($url, $pos + strlen($marker));
            $after = strtok($after, '?#') ?: $after;
            $localName = basename(rawurldecode($after));
            if ($localName !== '' && $localName !== '.' && $localName !== '..') {
                if ($this->blogImageExists($disk, $localName)) {
                    return $localName;
                }
                // File may exist only on disk after migrate; still prefer basename over empty.
                if (preg_match('/\.(jpe?g|png|webp|gif)$/i', $localName)) {
                    Log::warning('mcp.blog_image_local_missing', ['filename' => $localName, 'url' => $url]);

                    return $localName;
                }
            }
        }

        return $this->storeImageFromUrl($url);
    }

    public function storeImageFromUrl(?string $url): string
    {
        $url = trim((string) $url);
        if ($url === '') {
            return 'empty';
        }

        try {
            $bytes = $this->downloadImageBytes($url, 0);
            if ($bytes === null) {
                return 'empty';
            }

            $ext = $this->imageExtension($bytes);
            if ($ext === null) {
                Log::warning('mcp.blog_image_not_an_image', ['url' => $url]);

                return 'empty';
            }

            $name = Carbon::now()->format('Ymd') . '_' . uniqid('mcp_', true) . '.' . $ext;
            // public disk root is storage/app, and /storage maps to storage/app/public.
            $disk = Storage::disk('public');
            $this->migrateOrphanedBlogImages($disk);
            $disk->put('public/uploads/blog-images/' . $name, $bytes);

            return $name;
        } catch (\Throwable $e) {
            Log::error('mcp.blog_image_download_error', [
                'url' => $url,
                'error' => $e->getMessage(),
            ]);

            return 'empty';
        }
    }

    private function downloadImageBytes(string $url, int $depth): ?string
    {
        if ($depth > 2) {
            return null;
        }

        $response = Http::timeout(25)
            ->withOptions(['allow_redirects' => ['max' => 10, 'track_redirects' => true]])
            ->withHeaders([
                'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/122.0.0.0 Safari/537.36',
                'Accept' => 'image/avif,image/webp,image/apng,image/*,*/*;q=0.8',
            ])
            ->get($url);

        if (!$response->successful()) {
            Log::warning('mcp.blog_image_download_failed', [
                'url' => $url,
                'status' => $response->status(),
            ]);

            return null;
        }

        $body = $response->body();
        if ($body === '' || strlen($body) > 8 * 1024 * 1024) {
            Log::warning('mcp.blog_image_bad_size', ['url' => $url, 'bytes' => strlen($body)]);

            return null;
        }

        if ($this->imageExtension($body) !== null) {
            return $body;
        }

        $contentType = strtolower((string) $response->header('Content-Type'));
        $looksLikeHtml = str_contains($contentType, 'text/html') || str_starts_with(ltrim($body), '<');
        if ($looksLikeHtml && preg_match('/property=["\']og:image["\'][^>]*content=["\']([^"\']+)["\']/i', $body, $match)) {
            return $this->downloadImageBytes(html_entity_decode($match[1], ENT_QUOTES), $depth + 1);
        }
        if ($looksLikeHtml && preg_match('/content=["\']([^"\']+)["\'][^>]*property=["\']og:image["\']/i', $body, $match)) {
            return $this->downloadImageBytes(html_entity_decode($match[1], ENT_QUOTES), $depth + 1);
        }

        return null;
    }

    private function imageExtension(string $bytes): ?string
    {
        if (str_starts_with($bytes, "\xFF\xD8\xFF")) {
            return 'jpg';
        }
        if (str_starts_with($bytes, "\x89PNG\r\n\x1A\n")) {
            return 'png';
        }
        if (str_starts_with($bytes, 'GIF87a') || str_starts_with($bytes, 'GIF89a')) {
            return 'gif';
        }
        if (strlen($bytes) > 12 && str_starts_with($bytes, 'RIFF') && substr($bytes, 8, 4) === 'WEBP') {
            return 'webp';
        }

        return null;
    }

    private function blogImageExists($disk, string $filename): bool
    {
        return $disk->exists('public/uploads/blog-images/' . $filename)
            || $disk->exists('uploads/blog-images/' . $filename);
    }

    /**
     * Move legacy MCP uploads (storage/app/uploads/blog-images) into the
     * web-served path used by admin (storage/app/public/uploads/blog-images).
     */
    private function migrateOrphanedBlogImages($disk): void
    {
        $legacy = 'uploads/blog-images';
        $canonical = 'public/uploads/blog-images';

        if (!$disk->exists($legacy)) {
            return;
        }

        if (!$disk->exists($canonical)) {
            $disk->makeDirectory($canonical);
        }

        foreach ($disk->files($legacy) as $path) {
            $target = $canonical . '/' . basename($path);
            if (!$disk->exists($target)) {
                $disk->move($path, $target);
            } else {
                $disk->delete($path);
            }
        }
    }

    public function formatBlog(Blog $blog, bool $includeDescription): array
    {
        $rawStatus = (int) $blog->getRawOriginal('status');
        $blog->loadMissing(['categories:id,name,slug', 'tags:id,name,slug']);

        $data = [
            'id' => $blog->id,
            'title' => $blog->title,
            'slug' => $blog->slug,
            'status' => $rawStatus,
            'status_label' => $rawStatus === 1 ? 'Active' : 'Draft',
            'image' => $blog->image,
            'image_url' => $blog->image_url,
            'url' => url('/blogs/' . $blog->slug),
            'created_at' => optional($blog->created_at)->toDateTimeString(),
            'updated_at' => optional($blog->updated_at)->toDateTimeString(),
            'last_reviewed_at' => optional($blog->last_reviewed_at)->toDateTimeString(),
            'seo' => $this->formatSeo($blog),
            'schema' => $blog->schemaArray(),
            'categories' => $blog->categories->map->only(['id', 'name', 'slug'])->values(),
            'tags' => $blog->tags->map->only(['id', 'name', 'slug'])->values(),
        ];

        if ($includeDescription) {
            $data['description'] = $blog->description;
        }

        return $data;
    }

    /**
     * @return array<string, mixed>
     */
    public function formatSeo(Blog $blog): array
    {
        return [
            'meta_title' => $blog->meta_title,
            'meta_description' => $blog->meta_description,
            'canonical_url' => $blog->canonical_url,
            'robots' => $blog->robots,
            'resolved_title' => $blog->seoTitle(),
            'resolved_description' => $blog->seoDescription(160),
        ];
    }

    public function seoMeta(Request $request, $idOrSlug)
    {
        $blog = $this->findActiveBlog($idOrSlug);
        if (!$blog) {
            return response()->json(['success' => false, 'message' => 'Blog not found.'], 404);
        }

        return response()->json([
            'success' => true,
            'id' => $blog->id,
            'slug' => $blog->slug,
            'seo' => $this->formatSeo($blog),
        ]);
    }

    public function updateSeoMeta(Request $request, $idOrSlug)
    {
        $blog = $this->findActiveBlog($idOrSlug);
        if (!$blog) {
            return response()->json(['success' => false, 'message' => 'Blog not found.'], 404);
        }

        $input = $this->jsonBody($request);
        $map = [
            'meta_title' => ['meta_title', 'seo_title', 'title'],
            'meta_description' => ['meta_description', 'seo_description', 'description'],
            'canonical_url' => ['canonical_url', 'canonical'],
            'robots' => ['robots'],
        ];
        $provided = [];
        foreach ($map as $column => $keys) {
            foreach ($keys as $key) {
                if (array_key_exists($key, $input)) {
                    $provided[$column] = $input[$key];
                    break;
                }
            }
        }

        if ($provided === []) {
            return response()->json([
                'success' => false,
                'message' => 'No SEO fields were provided. Send meta_title, meta_description, canonical_url, or robots as JSON.',
            ], 422);
        }

        $warnings = [];
        if (array_key_exists('meta_title', $provided)) {
            $blog->meta_title = $this->blankToNull($provided['meta_title'], 255, $warnings, 'meta_title');
        }
        if (array_key_exists('meta_description', $provided)) {
            $blog->meta_description = $this->blankToNull($provided['meta_description'], 512, $warnings, 'meta_description');
        }
        if (array_key_exists('robots', $provided)) {
            $blog->robots = $this->blankToNull($provided['robots'], 64, $warnings, 'robots');
        }
        if (array_key_exists('canonical_url', $provided)) {
            $canonical = $this->blankToNull($provided['canonical_url'], 2048, $warnings, 'canonical_url');
            if ($canonical !== null && filter_var($canonical, FILTER_VALIDATE_URL) === false) {
                $warnings[] = 'canonical_url was not a valid URL and was not saved.';
            } else {
                $blog->canonical_url = $canonical;
            }
        }

        $blog->save();

        return response()->json([
            'success' => true,
            'message' => 'SEO meta updated',
            'id' => $blog->id,
            'slug' => $blog->slug,
            'warnings' => $warnings,
            'seo' => $this->formatSeo($blog->fresh()),
        ]);
    }

    public function schema(Request $request, $idOrSlug)
    {
        $blog = $this->findActiveBlog($idOrSlug);
        if (!$blog) {
            return response()->json(['success' => false, 'message' => 'Blog not found.'], 404);
        }

        return response()->json([
            'success' => true,
            'id' => $blog->id,
            'slug' => $blog->slug,
            'schema_json' => $blog->schema_json,
            'schema' => $blog->schemaArray(),
        ]);
    }

    public function updateSchema(Request $request, $idOrSlug)
    {
        $blog = $this->findActiveBlog($idOrSlug);
        if (!$blog) {
            return response()->json(['success' => false, 'message' => 'Blog not found.'], 404);
        }

        $input = $this->jsonBody($request);
        if (!array_key_exists('schema', $input) && !array_key_exists('schema_json', $input) && !array_key_exists('json_ld', $input)) {
            return response()->json([
                'success' => false,
                'message' => 'Provide schema (object or JSON string) or schema_json. Pass null to clear.',
            ], 422);
        }

        if (array_key_exists('json_ld', $input) && !array_key_exists('schema', $input)) {
            $input['schema'] = $input['json_ld'];
        }

        if (array_key_exists('schema', $input)) {
            $schema = $input['schema'];
            if (is_string($schema)) {
                $decoded = json_decode($schema, true);
                if (json_last_error() !== JSON_ERROR_NONE || !is_array($decoded)) {
                    return response()->json([
                        'success' => false,
                        'message' => 'schema string is not a JSON object or array.',
                    ], 422);
                }
                $schema = $decoded;
            }
            if ($schema === null || $schema === '') {
                $blog->schema_json = null;
            } elseif (is_array($schema) || is_object($schema)) {
                $blog->schema_json = json_encode($schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'schema must be a JSON object/array or null.',
                ], 422);
            }
        } else {
            $raw = $input['schema_json'] ?? null;
            if ($raw === null || $raw === '') {
                $blog->schema_json = null;
            } elseif (is_array($raw) || is_object($raw)) {
                $blog->schema_json = json_encode($raw, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
            } elseif (is_string($raw)) {
                $decoded = json_decode($raw, true);
                if (json_last_error() !== JSON_ERROR_NONE) {
                    return response()->json([
                        'success' => false,
                        'message' => 'schema_json is not valid JSON.',
                    ], 422);
                }
                $blog->schema_json = json_encode($decoded, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid schema_json value.',
                ], 422);
            }
        }

        $blog->save();

        return response()->json([
            'success' => true,
            'message' => 'Schema updated',
            'id' => $blog->id,
            'slug' => $blog->slug,
            'schema_json' => $blog->schema_json,
            'schema' => $blog->fresh()->schemaArray(),
        ]);
    }
}
