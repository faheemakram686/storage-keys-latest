<?php

namespace App\Services\Mcp;

/**
 * Turns stored DB content into what visitors see: expands component markers
 * and {{placeholders}} (App\Services\Mcp\Placeholders). Content without markers comes back unchanged.
 */
class ContentRenderer
{
    public const MARKER = '/<!--sk-component:([a-z_]+) (\{.*?\})-->/s';

    public static function render(?string $html, ?object $page = null): string
    {
        $html = (string) $html;
        $pageId = $page instanceof \App\Models\Page ? (int) $page->id : null;

        if (str_contains($html, '<!--sk-component:')) {
            $html = preg_replace_callback(self::MARKER, static function (array $m) use ($pageId) {
                $attrs = json_decode($m[2], true);

                return Components::render($m[1], is_array($attrs) ? $attrs : [], $pageId);
            }, $html);
        }

        return Placeholders::render($html, $pageId);
    }
}
