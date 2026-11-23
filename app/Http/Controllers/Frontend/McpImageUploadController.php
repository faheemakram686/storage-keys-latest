<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\ImageUploadLink;
use App\Services\Mcp\ImageStore;
use App\Services\Mcp\McpContext;
use App\Services\Mcp\McpException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * The one-time upload page from the MCP create_image_upload_link tool. The token in the URL is the only key.
 */
class McpImageUploadController extends Controller
{
    public function show(string $token)
    {
        $link = $this->link($token);

        return response()->view('mcp.image-upload', ['link' => $link, 'token' => $token, 'problems' => [], 'done' => []])
            ->header('X-Robots-Tag', 'noindex, nofollow')
            ->header('Referrer-Policy', 'no-referrer');
    }

    public function store(Request $request, string $token)
    {
        $link = $this->link($token);
        $errors = [];
        $done = [];

        if ($link->status() === 'waiting') {
            $files = array_slice((array) $request->file('images', []), 0, $link->remaining());
            $alts = (array) $request->input('alts', []);
            if ($files === []) {
                $errors[] = 'Choose at least one image.';
            }

            foreach ($files as $i => $file) {
                $alt = trim((string) ($alts[$i] ?? $link->alt ?? ''));
                try {
                    if (!$file || !$file->isValid()) {
                        throw new McpException('The upload did not arrive completely.');
                    }
                    $item = McpContext::as('create_image_upload_link', function () use ($file, $alt) {
                        McpContext::$source = 'upload-link';

                        return ImageStore::store((string) file_get_contents($file->getRealPath()), $alt, $file->getClientOriginalName(), 'upload-link');
                    });
                    DB::transaction(function () use ($link, $item) {
                        $fresh = ImageUploadLink::query()->lockForUpdate()->find($link->id);
                        $ids = array_merge($fresh->media_ids ?? [], [$item->id]);
                        $fresh->media_ids = $ids;
                        if (count($ids) >= $fresh->max_files) {
                            $fresh->completed_at = now();
                        }
                        $fresh->save();
                    });
                    $link->refresh();
                    $done[] = $file->getClientOriginalName();
                } catch (McpException $e) {
                    $errors[] = $file?->getClientOriginalName() . ': ' . $e->getMessage();
                }
            }
        }

        return response()->view('mcp.image-upload', ['link' => $link->fresh(), 'token' => $token, 'problems' => $errors, 'done' => $done])
            ->header('X-Robots-Tag', 'noindex, nofollow')
            ->header('Referrer-Policy', 'no-referrer');
    }

    private function link(string $token): ImageUploadLink
    {
        $link = strlen($token) === 48 ? ImageUploadLink::findByToken($token) : null;
        abort_if(!$link, 404);

        return $link;
    }
}
