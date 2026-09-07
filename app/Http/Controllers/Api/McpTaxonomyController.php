<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\BlogCategory;
use App\Models\BlogTag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class McpTaxonomyController extends Controller
{
    public function categories()
    {
        $items = BlogCategory::query()->orderBy('name')->get()->map(function (BlogCategory $c) {
            return [
                'id' => $c->id,
                'name' => $c->name,
                'slug' => $c->slug,
                'description' => $c->description,
                'blogs_count' => $c->blogs()->where('is_deleted', 0)->count(),
            ];
        });

        return response()->json(['success' => true, 'count' => $items->count(), 'categories' => $items]);
    }

    public function createCategory(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:120',
            'slug' => 'nullable|string|max:140',
            'description' => 'nullable|string|max:2000',
        ]);
        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => 'Validation error', 'errors' => $validator->errors()], 422);
        }

        $name = trim($request->input('name'));
        $slug = Str::slug($request->input('slug') ?: $name, '-');
        if ($slug === '') {
            $slug = 'category-' . time();
        }
        $slug = $this->uniqueCategorySlug($slug);

        $cat = BlogCategory::create([
            'name' => $name,
            'slug' => $slug,
            'description' => $request->input('description'),
        ]);

        return response()->json(['success' => true, 'category' => $cat], 201);
    }

    public function tags()
    {
        $items = BlogTag::query()->orderBy('name')->get()->map(function (BlogTag $t) {
            return [
                'id' => $t->id,
                'name' => $t->name,
                'slug' => $t->slug,
                'blogs_count' => $t->blogs()->where('is_deleted', 0)->count(),
            ];
        });

        return response()->json(['success' => true, 'count' => $items->count(), 'tags' => $items]);
    }

    public function createTag(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:80',
            'slug' => 'nullable|string|max:100',
        ]);
        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => 'Validation error', 'errors' => $validator->errors()], 422);
        }

        $name = trim($request->input('name'));
        $slug = Str::slug($request->input('slug') ?: $name, '-');
        if ($slug === '') {
            $slug = 'tag-' . time();
        }
        $slug = $this->uniqueTagSlug($slug);

        $tag = BlogTag::create(['name' => $name, 'slug' => $slug]);

        return response()->json(['success' => true, 'tag' => $tag], 201);
    }

    public function setBlogTaxonomies(Request $request, $idOrSlug)
    {
        $blog = $this->findBlog($idOrSlug);
        if (!$blog) {
            return response()->json(['success' => false, 'message' => 'Blog not found.'], 404);
        }

        $validator = Validator::make($request->all(), [
            'category_ids' => 'nullable|array',
            'category_ids.*' => 'integer',
            'tag_ids' => 'nullable|array',
            'tag_ids.*' => 'integer',
            'category_slugs' => 'nullable|array',
            'category_slugs.*' => 'string',
            'tag_slugs' => 'nullable|array',
            'tag_slugs.*' => 'string',
        ]);
        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => 'Validation error', 'errors' => $validator->errors()], 422);
        }

        if ($request->exists('category_ids') || $request->exists('category_slugs')) {
            $ids = $request->input('category_ids', []);
            if ($request->filled('category_slugs')) {
                $ids = array_merge($ids, BlogCategory::query()->whereIn('slug', $request->input('category_slugs'))->pluck('id')->all());
            }
            $blog->categories()->sync(array_values(array_unique(array_map('intval', $ids))));
        }

        if ($request->exists('tag_ids') || $request->exists('tag_slugs')) {
            $ids = $request->input('tag_ids', []);
            if ($request->filled('tag_slugs')) {
                $ids = array_merge($ids, BlogTag::query()->whereIn('slug', $request->input('tag_slugs'))->pluck('id')->all());
            }
            $blog->tags()->sync(array_values(array_unique(array_map('intval', $ids))));
        }

        $blog->load(['categories:id,name,slug', 'tags:id,name,slug']);

        return response()->json([
            'success' => true,
            'message' => 'Taxonomies updated',
            'blog_id' => $blog->id,
            'categories' => $blog->categories,
            'tags' => $blog->tags,
        ]);
    }

    private function findBlog($idOrSlug): ?Blog
    {
        $q = Blog::query()->where('is_deleted', 0);
        if (is_numeric($idOrSlug)) {
            return $q->where('id', (int) $idOrSlug)->first();
        }

        return $q->where('slug', (string) $idOrSlug)->first();
    }

    private function uniqueCategorySlug(string $base): string
    {
        $slug = $base;
        $i = 1;
        while (BlogCategory::query()->where('slug', $slug)->exists()) {
            $slug = $base . '-' . $i++;
        }

        return $slug;
    }

    private function uniqueTagSlug(string $base): string
    {
        $slug = $base;
        $i = 1;
        while (BlogTag::query()->where('slug', $slug)->exists()) {
            $slug = $base . '-' . $i++;
        }

        return $slug;
    }
}
