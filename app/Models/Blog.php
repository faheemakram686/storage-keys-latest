<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'image',
        'description',
        'slug',
        'status',
        'is_deleted',
        'meta_title',
        'meta_description',
        'canonical_url',
        'robots',
        'schema_json',
    ];

    public function setStatusAttribute($value)
    {
        if ($value == 0) {
            $value = 0;
        }
        if ($value == 1) {
            $value = 1;
        }
        $this->attributes['status'] = $value;
    }

    public function getStatusAttribute($value)
    {
        if ($value == 1) {
            $getVal = 'Active';
        }
        if ($value == 0) {
            $getVal = 'In-Active';
        }

        return $getVal;
    }

    public function getImageUrlAttribute()
    {
        if (!empty($this->image) && $this->image !== 'empty') {
            return asset('storage/uploads/blog-images/' . $this->image);
        }

        return asset('sk-assets/assets/images/frontend/blog/Image_8.png');
    }

    public function excerpt($limit = 140)
    {
        $text = trim(preg_replace('/\s+/', ' ', strip_tags((string) $this->description)));

        return \Illuminate\Support\Str::limit($text, $limit);
    }

    /** Resolved meta title with legacy fallback (does not break old pages). */
    public function seoTitle(): string
    {
        $custom = trim((string) ($this->meta_title ?? ''));

        return $custom !== '' ? $custom : ($this->title . ' | Storage Keys');
    }

    /** Resolved meta description with excerpt fallback. */
    public function seoDescription(int $limit = 160): string
    {
        $custom = trim((string) ($this->meta_description ?? ''));

        return $custom !== '' ? $custom : $this->excerpt($limit);
    }

    /** @return array<int|string, mixed>|null */
    public function schemaArray(): ?array
    {
        $raw = trim((string) ($this->schema_json ?? ''));
        if ($raw === '') {
            return null;
        }

        $decoded = json_decode($raw, true);

        return is_array($decoded) ? $decoded : null;
    }
}
