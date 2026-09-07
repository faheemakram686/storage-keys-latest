<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;
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
            'slug' => 'nullable|string|max:255',
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
        $imageName = $this->storeImageFromUrl($request->input('image_url'));

        $blog = new Blog();
        $blog->title = $title;
        $blog->description = $description;
        $blog->slug = $slug;
        $blog->image = $imageName;
        $blog->status = $status;
        $blog->is_deleted = 0;
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
            'slug' => 'nullable|string|max:255',
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

        if ($request->filled('image_url')) {
            $imageName = $this->storeImageFromUrl($request->input('image_url'));
            if ($imageName !== 'empty') {
                $blog->image = $imageName;
            }
        }

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

    public function storeImageFromUrl(?string $url): string
    {
        if (!$url) {
            return 'empty';
        }

        try {
            $response = Http::timeout(20)
                ->withHeaders(['User-Agent' => 'StorageKeys-MCP-Blog/1.0'])
                ->get($url);

            if (!$response->successful()) {
                return 'empty';
            }

            $contentType = strtolower((string) $response->header('Content-Type'));
            $ext = 'jpg';
            if (str_contains($contentType, 'png')) {
                $ext = 'png';
            } elseif (str_contains($contentType, 'webp')) {
                $ext = 'webp';
            } elseif (str_contains($contentType, 'gif')) {
                $ext = 'gif';
            } elseif (str_contains($contentType, 'jpeg') || str_contains($contentType, 'jpg')) {
                $ext = 'jpg';
            } else {
                $pathExt = strtolower(pathinfo(parse_url($url, PHP_URL_PATH) ?? '', PATHINFO_EXTENSION));
                if (in_array($pathExt, ['jpg', 'jpeg', 'png', 'webp', 'gif'], true)) {
                    $ext = $pathExt === 'jpeg' ? 'jpg' : $pathExt;
                }
            }

            $name = Carbon::now()->format('Ymd') . '_' . uniqid('mcp_', true) . '.' . $ext;
            Storage::disk('public')->put('uploads/blog-images/' . $name, $response->body());

            return $name;
        } catch (\Throwable $e) {
            return 'empty';
        }
    }

    public function formatBlog(Blog $blog, bool $includeDescription): array
    {
        $rawStatus = (int) $blog->getRawOriginal('status');

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
        ];

        if ($includeDescription) {
            $data['description'] = $blog->description;
        }

        return $data;
    }
}
