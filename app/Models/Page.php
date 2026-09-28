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
     * already starts with a full hero block (like static service pages).
     */
    public function shouldHideBanner(): bool
    {
        if ((bool) $this->hide_banner) {
            return true;
        }

        return $this->contentStartsWithHero();
    }

    public function contentStartsWithHero(): bool
    {
        $html = trim((string) $this->content);
        if ($html === '') {
            return false;
        }

        // Ignore leading <style>…</style> blocks used by designed CMS pages.
        $withoutStyles = preg_replace('/^(?:\s*<style\b[^>]*>.*?<\/style>\s*)+/is', '', $html);
        $probe = trim((string) $withoutStyles);

        // First meaningful chunk (before too much noise).
        $head = Str::lower(Str::limit($probe, 1200, ''));

        $patterns = [
            'skp-hero',
            'ps-hero',
            'svc-hero',
            'class="hero',
            "class='hero",
            'class="sk-hero',
            "class='sk-hero",
        ];

        foreach ($patterns as $needle) {
            if (Str::contains($head, $needle)) {
                // Prefer hero near the start of body content.
                $pos = strpos($head, $needle);
                if ($pos !== false && $pos < 400) {
                    return true;
                }
            }
        }

        return (bool) preg_match('/<(section|div|header)\b[^>]*class=("|\')[^"\']*\bhero\b/i', substr($probe, 0, 800));
    }
}
