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
    /** Why the last image_url did not become a stored file. Empty when it saved. */
    private string $lastImageError = '';

    public function lastImageError(): string
    {
        return $this->lastImageError;
    }

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
        $validator = Validator::make($this->jsonBody($request), [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'status' => 'nullable|in:0,1',
            'image_url' => 'nullable|string|max:16000000',
            'image' => 'nullable|string|max:255',
            'slug' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors(),
            ], 422);
        }

        $input = $this->jsonBody($request);
        $title = trim((string) ($input['title'] ?? ''));
        $description = $input['description'] ?? '';
        // Default draft for MCP uploads — review before publishing
        $status = array_key_exists('status', $input) ? (int) $input['status'] : 0;

        $slugBase = !empty($input['slug'])
            ? Str::slug((string) $input['slug'], '-')
            : Str::slug($title, '-');

        if ($slugBase === '') {
            $slugBase = 'blog-' . Carbon::now()->format('YmdHis');
        }

        $slug = $this->uniqueSlug($slugBase);
        // Image is independent of status — drafts must keep featured images too.
        $imageName = $this->resolveBlogImage(
            $input['image_url'] ?? null,
            $input['image'] ?? null
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

        return response()->json($this->withImageError([
            'success' => true,
            'message' => 'Blog created successfully',
            'blog' => $this->formatBlog($blog->fresh(), true),
        ]), 201);
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

        $validator = Validator::make($this->jsonBody($request), [
            'title' => 'sometimes|required|string|max:255',
            'description' => 'sometimes|required|string',
            'status' => 'nullable|in:0,1',
            'image_url' => 'nullable|string|max:16000000',
            'image' => 'nullable|string|max:255',
            'slug' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors(),
            ], 422);
        }

        $input = $this->jsonBody($request);

        if (isset($input['title']) && trim((string) $input['title']) !== '') {
            $blog->title = trim((string) $input['title']);
        }

        if (array_key_exists('description', $input)) {
            $blog->description = $input['description'];
        }

        if (array_key_exists('status', $input) && in_array((string) $input['status'], ['0', '1'], true)) {
            $blog->status = (int) $input['status'];
        }

        // Only change slug when explicitly provided (never auto-rewrite on title-only updates).
        if (!empty($input['slug'])) {
            $slugBase = Str::slug((string) $input['slug'], '-');
            if ($slugBase === '') {
                $slugBase = 'blog-' . Carbon::now()->format('YmdHis');
            }
            $blog->slug = $this->uniqueSlug($slugBase, (int) $blog->id);
        }

        $imageUrl = $this->coerceImageUrl($input['image_url'] ?? null);
        $imageFile = isset($input['image']) && is_string($input['image']) ? trim($input['image']) : '';
        if ($imageUrl !== '' || $imageFile !== '') {
            $imageName = $this->resolveBlogImage($imageUrl, $imageFile);
            if ($imageName !== 'empty') {
                $blog->image = $imageName;
            }
        }

        $this->applySeoFields($blog, $request);
        $blog->save();

        return response()->json($this->withImageError([
            'success' => true,
            'message' => 'Blog updated successfully',
            'blog' => $this->formatBlog($blog->fresh(), true),
        ]));
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
            if ($field === 'canonical_url') {
                $normalized = $this->normalizeCanonical($value);
                if ($value !== null && $normalized === null) {
                    continue;
                }
                $value = $normalized;
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
            return $this->expandWriteInput(is_array($data) ? $data : []);
        }
        $decoded = json_decode($raw, true);
        if (!is_array($decoded)) {
            return $this->expandWriteInput(is_array($data) ? $data : []);
        }

        return $this->expandWriteInput(array_merge($decoded, is_array($data) ? $data : []));
    }

    /**
     * Accept the field names the daily automation actually sends.
     *
     * @param  array<string, mixed>  $input
     * @return array<string, mixed>
     */
    private function expandWriteInput(array $input): array
    {
        if (isset($input['seo']) && is_array($input['seo'])) {
            $seo = $input['seo'];
            $this->liftKey($input, 'meta_title', $seo, ['meta_title', 'seo_title', 'metaTitle', 'title']);
            $this->liftKey($input, 'meta_description', $seo, ['meta_description', 'seo_description', 'metaDescription', 'description']);
            $this->liftKey($input, 'canonical_url', $seo, ['canonical_url', 'canonical', 'canonicalUrl']);
            $this->liftKey($input, 'robots', $seo, ['robots']);
        }

        $aliases = [
            'meta_title' => ['seo_title', 'metaTitle', 'seoTitle'],
            'meta_description' => ['seo_description', 'metaDescription', 'seoDescription'],
            'canonical_url' => ['canonical', 'canonicalUrl'],
            'image_url' => ['imageUrl', 'featured_image', 'featuredImage', 'cover_image', 'coverImage'],
            'schema' => ['json_ld', 'jsonLd', 'structured_data', 'structuredData'],
        ];
        foreach ($aliases as $target => $sources) {
            if (array_key_exists($target, $input)) {
                continue;
            }
            foreach ($sources as $source) {
                if (array_key_exists($source, $input)) {
                    $input[$target] = $input[$source];
                    break;
                }
            }
        }

        if (isset($input['image_url']) && !is_string($input['image_url'])) {
            $input['image_url'] = $this->coerceImageUrl($input['image_url']);
        }
        if (isset($input['robots']) && is_array($input['robots'])) {
            $input['robots'] = implode(',', array_map('strval', $input['robots']));
        }

        return $input;
    }

    /**
     * @param  array<string, mixed>  $input
     * @param  array<string, mixed>  $source
     * @param  list<string>  $keys
     */
    private function liftKey(array &$input, string $target, array $source, array $keys): void
    {
        if (array_key_exists($target, $input)) {
            return;
        }
        foreach ($keys as $key) {
            if (array_key_exists($key, $source)) {
                $input[$target] = $source[$key];
                return;
            }
        }
    }

    /**
     * @param  mixed  $value
     */
    private function coerceImageUrl($value): string
    {
        if (is_string($value)) {
            return trim($value);
        }
        if (!is_array($value)) {
            return '';
        }
        foreach (['url', 'src', 'href', 'image_url', 'imageUrl'] as $key) {
            if (!empty($value[$key]) && is_string($value[$key])) {
                return trim($value[$key]);
            }
        }

        return '';
    }

    private function normalizeCanonical(?string $value): ?string
    {
        if ($value === null || $value === '') {
            return null;
        }
        if (str_starts_with($value, '/')) {
            $value = 'https://storagekeys.com' . $value;
        }
        if (filter_var($value, FILTER_VALIDATE_URL) === false) {
            return null;
        }

        return $value;
    }

    /**
     * @param  array<string, mixed>  $payload
     * @return array<string, mixed>
     */
    private function withImageError(array $payload): array
    {
        if ($this->lastImageError !== '') {
            $payload['image_error'] = $this->lastImageError;
            $payload['image_saved'] = false;
        }

        return $payload;
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
    public function resolveBlogImage($url, $filename = null): string
    {
        $this->lastImageError = '';
        $url = $this->coerceImageUrl($url);
        $disk = Storage::disk('public');
        $this->migrateOrphanedBlogImages($disk);

        $filename = trim((string) $filename);
        if ($filename !== '') {
            $filename = basename($filename);
            if ($this->blogImageExists($disk, $filename)) {
                return $filename;
            }
        }

        $url = $this->normalizeRemoteImageUrl($url);
        if ($url === '') {
            $this->lastImageError = 'image_url was empty.';

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
                if (preg_match('/\.(jpe?g|png|webp|gif|avif)$/i', $localName)) {
                    Log::warning('mcp.blog_image_local_missing', ['filename' => $localName, 'url' => $url]);

                    return $localName;
                }
            }
        }

        return $this->storeImageFromUrl($url);
    }

    public function storeImageFromUrl(?string $url): string
    {
        $url = $this->normalizeRemoteImageUrl((string) $url);
        if ($url === '') {
            $this->lastImageError = 'image_url was empty.';

            return 'empty';
        }

        try {
            $bytes = $this->inlineImageBytes($url) ?? $this->downloadImageBytes($url, 0);
            if ($bytes === null) {
                if ($this->lastImageError === '') {
                    $this->lastImageError = 'Could not download an image from image_url.';
                }

                return 'empty';
            }

            $ext = $this->imageExtension($bytes);
            if ($ext === null) {
                $this->lastImageError = 'Downloaded file is not a jpeg, png, gif, webp, or avif image.';
                Log::warning('mcp.blog_image_not_an_image', ['url' => $url]);

                return 'empty';
            }

            $name = Carbon::now()->format('Ymd') . '_' . uniqid('mcp_', true) . '.' . $ext;
            // public disk root is storage/app, and /storage maps to storage/app/public.
            $disk = Storage::disk('public');
            $this->migrateOrphanedBlogImages($disk);
            $directory = 'public/uploads/blog-images';
            if (!$disk->exists($directory)) {
                $disk->makeDirectory($directory);
            }
            $written = $disk->put($directory . '/' . $name, $bytes);
            if ($written === false) {
                $this->lastImageError = 'Storage rejected the file. Check write permission on storage/app/public/uploads/blog-images.';
                Log::error('mcp.blog_image_store_failed', ['url' => $url, 'path' => $directory . '/' . $name]);

                return 'empty';
            }

            $this->lastImageError = '';

            return $name;
        } catch (\Throwable $e) {
            $this->lastImageError = 'Image download failed: ' . $e->getMessage();
            Log::error('mcp.blog_image_download_error', [
                'url' => $url,
                'error' => $e->getMessage(),
            ]);

            return 'empty';
        }
    }

    private function normalizeRemoteImageUrl(string $url): string
    {
        $url = trim($url);
        if ($url === '') {
            return '';
        }
        if (preg_match('/!\[[^\]]*]\((https?:\/\/[^)\s]+)\)/', $url, $match)) {
            $url = $match[1];
        } elseif (!str_starts_with($url, 'data:image/') && preg_match('/https?:\/\/\S+/i', $url, $match)) {
            $url = rtrim($match[0], ').,\'"');
        }

        return trim($url);
    }

    private function inlineImageBytes(string $url): ?string
    {
        if (!str_starts_with($url, 'data:image/')) {
            return null;
        }
        $comma = strpos($url, ',');
        if ($comma === false) {
            $this->lastImageError = 'image_url data URI is missing image bytes.';

            return null;
        }
        $meta = substr($url, 0, $comma);
        $payload = substr($url, $comma + 1);
        $bytes = str_contains($meta, ';base64') ? base64_decode($payload, true) : rawurldecode($payload);
        if (!is_string($bytes) || $bytes === '') {
            $this->lastImageError = 'image_url data URI could not be decoded.';

            return null;
        }

        return $bytes;
    }

    private function downloadImageBytes(string $url, int $depth): ?string
    {
        if ($depth > 2) {
            $this->lastImageError = 'Image page did not lead to an image file.';

            return null;
        }

        $headers = [
            'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/122.0.0.0 Safari/537.36',
            'Accept' => 'image/avif,image/webp,image/apng,image/*,*/*;q=0.8',
        ];
        $parts = parse_url($url);
        if (!empty($parts['host'])) {
            $headers['Referer'] = ($parts['scheme'] ?? 'https') . '://' . $parts['host'] . '/';
        }

        $response = Http::timeout(25)
            ->withOptions(['allow_redirects' => ['max' => 10, 'track_redirects' => true]])
            ->withHeaders($headers)
            ->get($url);

        if (!$response->successful()) {
            $this->lastImageError = 'Image host returned HTTP ' . $response->status() . '.';
            Log::warning('mcp.blog_image_download_failed', [
                'url' => $url,
                'status' => $response->status(),
            ]);

            return null;
        }

        $body = $response->body();
        if ($body === '' || strlen($body) > 12 * 1024 * 1024) {
            $this->lastImageError = 'Image was empty or larger than 12MB.';
            Log::warning('mcp.blog_image_bad_size', ['url' => $url, 'bytes' => strlen($body)]);

            return null;
        }

        if ($this->imageExtension($body) !== null) {
            return $body;
        }

        $contentType = strtolower((string) $response->header('Content-Type'));
        $looksLikeHtml = str_contains($contentType, 'text/html') || str_starts_with(ltrim($body), '<');
        if ($looksLikeHtml) {
            $next = $this->htmlImageUrl($body, $url);
            if ($next !== null && $next !== $url) {
                return $this->downloadImageBytes($next, $depth + 1);
            }
            $this->lastImageError = 'Page has no og:image or twitter:image to download.';

            return null;
        }

        $this->lastImageError = 'Downloaded file is not a jpeg, png, gif, webp, or avif image.';

        return null;
    }

    private function htmlImageUrl(string $html, string $base): ?string
    {
        $patterns = [
            '/property=["\']og:image(?::url)?["\'][^>]*content=["\']([^"\']+)["\']/i',
            '/content=["\']([^"\']+)["\'][^>]*property=["\']og:image(?::url)?["\']/i',
            '/name=["\']twitter:image(?::src)?["\'][^>]*content=["\']([^"\']+)["\']/i',
            '/content=["\']([^"\']+)["\'][^>]*name=["\']twitter:image(?::src)?["\']/i',
        ];
        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $html, $match)) {
                return $this->absoluteUrl(html_entity_decode($match[1], ENT_QUOTES), $base);
            }
        }

        return null;
    }

    private function absoluteUrl(string $maybe, string $base): ?string
    {
        $maybe = trim($maybe);
        if ($maybe === '') {
            return null;
        }
        if (str_starts_with($maybe, '//')) {
            return 'https:' . $maybe;
        }
        if (preg_match('#^https?://#i', $maybe)) {
            return $maybe;
        }
        $parts = parse_url($base);
        if (empty($parts['host'])) {
            return null;
        }
        $origin = ($parts['scheme'] ?? 'https') . '://' . $parts['host'];
        if (str_starts_with($maybe, '/')) {
            return $origin . $maybe;
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
        if (strlen($bytes) > 12 && substr($bytes, 4, 4) === 'ftyp') {
            $brand = substr($bytes, 8, 4);
            if ($brand === 'avif' || $brand === 'avis') {
                return 'avif';
            }
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
            $normalized = $this->normalizeCanonical($canonical);
            if ($canonical !== null && $normalized === null) {
                $warnings[] = 'canonical_url was not a valid URL and was not saved.';
            } else {
                $blog->canonical_url = $normalized;
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
            if (isset($input['@context']) || isset($input['@graph']) || isset($input['@type'])) {
                $schema = $input;
                unset($schema['id'], $schema['slug'], $schema['schema_json']);
                $input['schema'] = $schema;
            }
        }
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
