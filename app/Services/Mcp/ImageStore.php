<?php

namespace App\Services\Mcp;

use App\Models\Blog;
use App\Models\MediaItem;
use App\Models\Page;
use App\Models\PageSection;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\ImageManager;

/**
 * Image files for MCP tools: safe download, WebP conversion, usage search and trash.
 * Files live in storage/app/public/uploads/mcp-images (served at /storage/uploads/mcp-images);
 * trashed files move to storage/app/mcp-trash, outside the web root.
 */
class ImageStore
{
    public const DIR = 'uploads/mcp-images';
    private const MAX_BYTES = 10 * 1024 * 1024;
    private const MAX_WIDTH = 2000;
    private const TYPES = [IMAGETYPE_JPEG, IMAGETYPE_PNG, IMAGETYPE_GIF, IMAGETYPE_WEBP];

    /** Download an image from a public http(s) URL. Private and local addresses are refused. */
    public static function fetch(string $url): string
    {
        for ($hop = 0; $hop < 4; $hop++) {
            $ip = self::assertPublicUrl($url);
            // Connect to the address that was checked, so DNS cannot switch to a private one in between.
            $host = (string) parse_url($url, PHP_URL_HOST);
            $port = (int) (parse_url($url, PHP_URL_PORT) ?: (strtolower((string) parse_url($url, PHP_URL_SCHEME)) === 'https' ? 443 : 80));
            $response = Http::timeout(25)->withoutRedirecting()
                ->withOptions(['curl' => [CURLOPT_RESOLVE => [$host . ':' . $port . ':' . (str_contains($ip, ':') ? '[' . $ip . ']' : $ip)]]])
                ->withHeaders(['Accept' => 'image/avif,image/webp,image/*;q=0.8', 'User-Agent' => 'StorageKeys-MCP/1.0'])
                ->get($url);
            if ($response->redirect()) {
                $next = (string) $response->header('Location');
                $url = preg_match('#^https?://#i', $next) ? $next : rtrim(preg_replace('#^(https?://[^/]+).*$#i', '$1', $url), '/') . '/' . ltrim($next, '/');
                continue;
            }
            if (!$response->successful()) {
                throw new McpException('The image URL returned HTTP ' . $response->status() . '.', 422);
            }
            $body = $response->body();
            if (strlen($body) > self::MAX_BYTES) {
                throw new McpException('Image is larger than 10 MB.', 422);
            }

            return $body;
        }

        throw new McpException('Too many redirects.', 422);
    }

    /** @return string the checked IP address */
    public static function assertPublicUrl(string $url): string
    {
        $parts = parse_url($url);
        if (!in_array(strtolower($parts['scheme'] ?? ''), ['http', 'https'], true) || empty($parts['host'])) {
            throw new McpException('url must be an http(s) URL.', 422);
        }
        $host = trim($parts['host'], '[]');
        $ips = filter_var($host, FILTER_VALIDATE_IP) ? [$host] : array_merge(
            array_column(@dns_get_record($host, DNS_A) ?: [], 'ip'),
            array_column(@dns_get_record($host, DNS_AAAA) ?: [], 'ipv6')
        );
        if ($ips === []) {
            throw new McpException('Could not resolve ' . $host . '.', 422);
        }
        foreach ($ips as $ip) {
            if (!filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) {
                throw new McpException('Images can only be fetched from public internet addresses.', 422);
            }
        }

        return $ips[0];
    }

    public static function fromBase64(string $data): string
    {
        $data = trim($data);
        if (preg_match('#^data:([^;,]+)?(;base64)?,#i', $data, $m)) {
            $data = substr($data, strlen($m[0]));
        }
        if (strlen($data) > self::MAX_BYTES * 1.4) {
            throw new McpException('Image is larger than 10 MB.', 422);
        }
        $bytes = base64_decode(preg_replace('/\s+/', '', $data), true);
        if ($bytes === false || $bytes === '') {
            throw new McpException('base64 is not valid base64 image data.', 422);
        }

        return $bytes;
    }

    /** Validate, convert to WebP (max 2000 px wide) and register. */
    public static function store(string $bytes, string $alt, ?string $filename = null, string $source = 'mcp'): MediaItem
    {
        $alt = trim(strip_tags($alt));
        if ($alt === '') {
            throw new McpException('alt is required: describe what the image shows.', 422);
        }
        if (strlen($bytes) > self::MAX_BYTES) {
            throw new McpException('Image is larger than 10 MB.', 422);
        }
        $info = @getimagesizefromstring($bytes);
        if (!$info || !in_array($info[2], self::TYPES, true)) {
            throw new McpException('The file is not a JPEG, PNG, GIF or WebP image.', 422);
        }

        $image = (new ImageManager(['driver' => 'gd']))->make($bytes);
        if ($image->width() > self::MAX_WIDTH) {
            $image->resize(self::MAX_WIDTH, null, function ($c) {
                $c->aspectRatio();
                $c->upsize();
            });
        }
        $webp = (string) $image->encode('webp', 82);

        $base = Str::slug(pathinfo((string) ($filename ?: $alt), PATHINFO_FILENAME)) ?: 'image';
        $path = self::DIR . '/' . Str::limit($base, 60, '') . '-' . Str::lower(Str::random(6)) . '.webp';
        Storage::disk('public')->put('public/' . $path, $webp);

        return MediaItem::create([
            'path' => $path,
            'alt' => mb_substr($alt, 0, 255),
            'width' => $image->width(),
            'height' => $image->height(),
            'bytes' => strlen($webp),
            'mime' => 'image/webp',
            'source' => $source,
            'is_deleted' => false,
        ]);
    }

    /** "/storage/uploads/x.webp", full URL or "uploads/x.webp" → "uploads/x.webp". */
    public static function relative(string $pathOrUrl): string
    {
        $path = (string) (parse_url(trim($pathOrUrl), PHP_URL_PATH) ?: $pathOrUrl);
        $path = ltrim(rawurldecode($path), '/');
        if (str_starts_with($path, 'storage/')) {
            $path = substr($path, 8);
        }
        if (str_contains($path, '..')) {
            throw new McpException('Invalid image path.', 422);
        }

        return $path;
    }

    /** Find (or register an existing file as) a media item. */
    public static function item(string $pathOrUrl, bool $withTrashed = false): MediaItem
    {
        $path = self::relative($pathOrUrl);
        $q = MediaItem::query()->where('path', $path);
        if (!$withTrashed) {
            $q->where('is_deleted', 0);
        }
        if ($item = $q->first()) {
            return $item;
        }
        $file = storage_path('app/public/' . $path);
        if (!$withTrashed && is_file($file) && ($info = @getimagesize($file))) {
            return MediaItem::create([
                'path' => $path,
                'width' => $info[0],
                'height' => $info[1],
                'bytes' => filesize($file),
                'mime' => $info['mime'] ?? null,
                'source' => str_starts_with($path, 'uploads/blog-images/') ? 'blog-images' : 'existing',
            ]);
        }

        throw McpException::notFound('Image ' . $pathOrUrl);
    }

    /** Where an image is used: DB pages, sections, blog bodies, blog featured images, coded templates. */
    public static function usage(MediaItem $item): array
    {
        $name = basename($item->path);
        $like = '%' . str_replace(['%', '_'], ['\%', '\_'], $name) . '%';
        $uses = [];

        foreach (Page::query()->active()->where('content', 'like', $like)->get(['id', 'slug']) as $p) {
            $uses[] = 'page /' . $p->slug;
        }
        foreach (Blog::query()->where('is_deleted', 0)->where(fn ($q) => $q->where('description', 'like', $like)->orWhere('image', $name))->get(['id', 'slug', 'image']) as $b) {
            $uses[] = 'blog /blogs/' . $b->slug . ($b->image === $name ? ' (featured image)' : '');
        }
        foreach (PageSection::query()->active()->where('is_visible', 0)->where('html', 'like', $like)->get(['id', 'page_id']) as $s) {
            $uses[] = 'hidden section ' . $s->id . ' (page id ' . $s->page_id . ')';
        }
        foreach (File::allFiles(resource_path('views')) as $file) {
            if (str_ends_with($file->getFilename(), '.blade.php') && str_contains((string) file_get_contents($file->getPathname()), $name)) {
                $uses[] = 'coded template ' . str_replace('\\', '/', $file->getRelativePathname());
            }
        }

        return array_values(array_unique($uses));
    }

    public static function moveToTrash(MediaItem $item): void
    {
        self::move(storage_path('app/public/' . $item->path), storage_path('app/mcp-trash/' . $item->path));
    }

    public static function moveFromTrash(MediaItem $item): void
    {
        self::move(storage_path('app/mcp-trash/' . $item->path), storage_path('app/public/' . $item->path));
    }

    public static function deleteFiles(MediaItem $item): void
    {
        foreach ([storage_path('app/mcp-trash/' . $item->path), storage_path('app/public/' . $item->path)] as $file) {
            if (is_file($file)) {
                @unlink($file);
            }
        }
    }

    private static function move(string $from, string $to): void
    {
        if (!is_file($from)) {
            return;
        }
        File::ensureDirectoryExists(dirname($to));
        File::move($from, $to);
    }
}
