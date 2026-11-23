<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * An image file in storage/app/public with its alt text. Uploaded through MCP image tools.
 */
class MediaItem extends Model
{
    protected $fillable = ['path', 'alt', 'title', 'width', 'height', 'bytes', 'mime', 'source', 'is_deleted'];

    public function url(): string
    {
        return '/storage/' . ltrim($this->path, '/');
    }

    public function absoluteUrl(): string
    {
        return url($this->url());
    }

    public function html(): string
    {
        $attrs = [
            'src' => $this->url(),
            'alt' => (string) $this->alt,
        ];
        if ($this->width && $this->height) {
            $attrs['width'] = (string) $this->width;
            $attrs['height'] = (string) $this->height;
        }
        $attrs['loading'] = 'lazy';

        $out = '<img';
        foreach ($attrs as $k => $v) {
            $out .= ' ' . $k . '="' . htmlspecialchars($v, ENT_QUOTES | ENT_HTML5, 'UTF-8') . '"';
        }

        return $out . '>';
    }
}
