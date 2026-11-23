<?php

namespace App\Services\Mcp;

/**
 * Turns stored DB content into what visitors see: expands component markers
 * (and, from phase 8, settings placeholders). Content without markers comes back unchanged.
 */
class ContentRenderer
{
    public const MARKER = '/<!--sk-component:([a-z_]+) (\{.*?\})-->/s';

    public static function render(?string $html): string
    {
        $html = (string) $html;
        if (!str_contains($html, '<!--sk-component:')) {
            return $html;
        }

        return preg_replace_callback(self::MARKER, static function (array $m) {
            $attrs = json_decode($m[2], true);

            return Components::render($m[1], is_array($attrs) ? $attrs : []);
        }, $html);
    }
}
