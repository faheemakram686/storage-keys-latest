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
        'hide_banner',
    ];

    protected $casts = [
        'hide_banner' => 'boolean',
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

    /**
     * Suppress default breadcrumb + auto H1 when flagged or when content
     * already includes a full hero section (like static service pages).
     */
    public function shouldHideBanner(): bool
    {
        if ($this->hide_banner === true || $this->hide_banner === 1 || $this->hide_banner === '1') {
            return true;
        }

        return $this->contentStartsWithHero();
    }

    /**
     * Detect a real HTML hero section (not merely a CSS class name in <style>).
     */
    public function contentStartsWithHero(): bool
    {
        $html = trim((string) $this->content);
        if ($html === '') {
            return false;
        }

        // Prefer matching an actual tag with a hero class (works even with a large leading <style>).
        if (preg_match(
            '/<(?:section|div|header)\b[^>]*\bclass\s*=\s*(["\'])[^"\']*\b(?:skp-hero|ps-hero|svc-hero|sk-hero)\b[^"\']*\1/i',
            $html
        )) {
            return true;
        }

        // Generic: first landmark tag class contains "hero".
        return (bool) preg_match(
            '/<(?:section|div|header)\b[^>]*\bclass\s*=\s*(["\'])[^"\']*\bhero\b[^"\']*\1/i',
            $html
        );
    }
}
