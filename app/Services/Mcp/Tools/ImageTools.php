<?php

namespace App\Services\Mcp\Tools;

use App\Models\ImageUploadLink;
use App\Models\MediaItem;
use App\Services\Mcp\ImageStore;
use App\Services\Mcp\McpException;
use App\Services\Mcp\Versioning;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

/**
 * Phase 6: image upload (URL, base64 or a one-time browser link), alt text, delete.
 */
class ImageTools extends Toolset
{
    public function definitions(): array
    {
        $path = ['type' => 'string', 'description' => 'Image path or URL, e.g. /storage/uploads/mcp-images/boxes-ab12cd.webp'];

        return [
            $this->tool('upload_image', 'Add an image from a public https URL or base64 data. It is converted to WebP (max 2000 px wide) and returned with a ready <img> tag. alt is required.', [
                'url' => ['type' => 'string'],
                'base64' => ['type' => 'string', 'description' => 'Base64 or data: URI'],
                'alt' => ['type' => 'string', 'description' => 'What the image shows'],
                'filename' => ['type' => 'string', 'description' => 'Optional base name for the file'],
            ], ['alt']),
            $this->tool('update_image', 'Change an image\'s stored alt text and/or title. To change the alt where the image is already placed, also run bulk_update_alt.', [
                'path' => $path,
                'alt' => ['type' => 'string'],
                'title' => ['type' => 'string'],
                'expected_updated_at' => ['type' => 'string', 'description' => 'Optional: refuse if the image record changed since'],
            ], ['path']),
            $this->tool('delete_image', 'Move an image to the trash (the file stops being served). Refused while the image is used on a page, post or coded template, unless force=true.', [
                'path' => $path,
                'force' => ['type' => 'boolean'],
            ], ['path']),
            $this->tool('create_image_upload_link', 'Make a one-time upload page (valid 24 hours) the owner opens in a browser to add images from their phone or computer. Give them the url; check the result with get_image_upload_link.', [
                'purpose' => ['type' => 'string', 'description' => 'Shown on the page, e.g. "Photos of the Al Quoz facility"'],
                'alt' => ['type' => 'string', 'description' => 'Default alt text the owner can change per image'],
                'max_files' => ['type' => 'integer', 'minimum' => 1, 'maximum' => 10],
            ], ['purpose']),
            $this->tool('get_image_upload_link', 'Status of an upload link (waiting, completed, expired) and the images uploaded through it.', [
                'link_id' => ['type' => 'integer'],
            ], ['link_id']),
        ];
    }

    public function call(string $name, array $a): array
    {
        switch ($name) {
            case 'upload_image':
                return $this->upload($a);
            case 'update_image':
                return $this->update($a);
            case 'delete_image':
                return $this->delete($a);
            case 'create_image_upload_link':
                return $this->createLink($a);
            case 'get_image_upload_link':
                return $this->getLink($a);
        }

        throw new McpException('Unknown tool: ' . $name, 404);
    }

    public static function present(MediaItem $m): array
    {
        return [
            'id' => $m->id,
            'path' => $m->url(),
            'url' => $m->absoluteUrl(),
            'alt' => $m->alt,
            'title' => $m->title,
            'width' => $m->width,
            'height' => $m->height,
            'kb' => $m->bytes ? round($m->bytes / 1024) : null,
            'html' => $m->html(),
            'updated_at' => Versioning::stamp($m),
        ];
    }

    private function upload(array $a): array
    {
        $alt = (string) $this->str($a, 'alt', true);
        $url = $this->str($a, 'url');
        $b64 = $this->str($a, 'base64');
        if (($url === null || $url === '') === ($b64 === null || $b64 === '')) {
            throw new McpException('Pass either url or base64.', 422);
        }
        $bytes = $url ? ImageStore::fetch($url) : ImageStore::fromBase64((string) $b64);
        $item = ImageStore::store($bytes, $alt, $this->str($a, 'filename') ?: ($url ? basename((string) parse_url($url, PHP_URL_PATH)) : null));

        return ['message' => 'Image uploaded as WebP.', 'image' => self::present($item)];
    }

    private function update(array $a): array
    {
        $item = ImageStore::item((string) $this->str($a, 'path', true));
        if (!empty($a['expected_updated_at'])) {
            Versioning::assertCurrent($item, $a['expected_updated_at'], 'Image');
        }
        foreach (['alt', 'title'] as $field) {
            if (array_key_exists($field, $a) && $a[$field] !== null) {
                $item->setAttribute($field, mb_substr(trim(strip_tags((string) $a[$field])), 0, 255) ?: null);
            }
        }
        if (!$item->isDirty()) {
            throw new McpException('Pass alt and/or title to change.', 422);
        }
        $item->save();

        return ['message' => 'Image updated. Run bulk_update_alt to change the alt where it is already placed.', 'image' => self::present($item->fresh())];
    }

    private function delete(array $a): array
    {
        $item = ImageStore::item((string) $this->str($a, 'path', true));
        $uses = ImageStore::usage($item);
        if ($uses && !$this->bool($a, 'force')) {
            throw new McpException('This image is in use; remove it there first or pass force=true.', 409, ['used_in' => $uses]);
        }

        DB::transaction(function () use ($item) {
            $item->is_deleted = 1;
            $item->save();
            ImageStore::moveToTrash($item);
        });

        return array_merge(
            ['message' => 'Image moved to the trash. restore_item with type=image and id=' . $item->id . ' brings it back.', 'id' => $item->id],
            $uses ? ['warning' => 'It was still used here; those places now show a broken image.', 'used_in' => $uses] : []
        );
    }

    private function createLink(array $a): array
    {
        $purpose = trim(strip_tags((string) $this->str($a, 'purpose', true)));
        $token = Str::random(48);
        $link = ImageUploadLink::create([
            'token_hash' => hash('sha256', $token),
            'purpose' => mb_substr($purpose, 0, 255),
            'alt' => $this->str($a, 'alt') ? mb_substr(trim(strip_tags((string) $a['alt'])), 0, 255) : null,
            'max_files' => $this->int($a, 'max_files', 1, false, 1, 10),
            'media_ids' => [],
            'expires_at' => now()->addDay(),
        ]);

        return [
            'message' => 'Send this link to the owner. It works once, for up to ' . $link->max_files . ' image(s), until ' . $link->expires_at->copy()->setTimezone('Asia/Dubai')->format('Y-m-d H:i') . ' Asia/Dubai.',
            'link_id' => $link->id,
            'url' => url('/mcp-upload/' . $token),
            'expires_at' => $link->expires_at->toDateTimeString(),
        ];
    }

    private function getLink(array $a): array
    {
        $link = ImageUploadLink::query()->find($this->int($a, 'link_id', null, true));
        if (!$link) {
            throw McpException::notFound('Upload link');
        }
        $items = MediaItem::query()->whereIn('id', $link->media_ids ?? [])->get();

        return [
            'link_id' => $link->id,
            'purpose' => $link->purpose,
            'status' => $link->status(),
            'uploaded' => $items->count(),
            'max_files' => (int) $link->max_files,
            'expires_at' => $link->expires_at->toDateTimeString(),
            'images' => $items->map(fn (MediaItem $m) => self::present($m))->values(),
        ];
    }
}
