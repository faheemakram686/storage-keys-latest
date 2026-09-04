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

    private function uniqueSlug(string $base): string
    {
        $slug = $base;
        $i = 1;

        while (
            Blog::query()
                ->where('slug', $slug)
                ->where('is_deleted', 0)
                ->exists()
        ) {
            $slug = $base . '-' . $i;
            $i++;
        }

        return $slug;
    }

    private function storeImageFromUrl(?string $url): string
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

    private function formatBlog(Blog $blog, bool $includeDescription): array
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
