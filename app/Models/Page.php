<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Page extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'content',
        'meta_title',
        'meta_description',
        'status',
        'is_deleted',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_deleted', 0);
    }

    public function scopePublished($query)
    {
        return $query->active()->where('status', 1);
    }

    public function seoTitle(): string
    {
        $custom = trim((string) ($this->meta_title ?? ''));

        return $custom !== '' ? $custom : ($this->title . ' | Storage Keys');
    }

    public function seoDescription(int $limit = 160): string
    {
        $custom = trim((string) ($this->meta_description ?? ''));
        if ($custom !== '') {
            return $custom;
        }

        $text = trim(preg_replace('/\s+/', ' ', strip_tags((string) $this->content)));

        return Str::limit($text, $limit);
    }
}
