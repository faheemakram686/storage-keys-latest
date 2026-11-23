<?php

namespace App\Services\Mcp;

/**
 * Targeted edits of stored HTML that leave every other byte as it was (no DOM re-serialisation).
 * The HTML is split into tags and text; only text outside <script>, <style> and comments is matched.
 */
class HtmlEdit
{
    /** @return list<array{0: string, 1: bool}> [chunk, isTag] */
    private static function tokens(string $html): array
    {
        $parts = preg_split('/(<!--.*?-->|<[^>]*>)/s', $html, -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY);

        return array_map(static fn ($p) => [$p, $p !== '' && $p[0] === '<'], $parts ?: []);
    }

    /**
     * Visit text chunks. $visit(string $text, bool $inLink): ?string returns the new text or null to keep it.
     */
    private static function mapText(string $html, callable $visit): string
    {
        $out = '';
        $inLink = 0;
        $skip = null;
        foreach (self::tokens($html) as [$chunk, $isTag]) {
            if ($isTag) {
                if ($skip !== null) {
                    if (preg_match('#^</' . $skip . '\s*>#i', $chunk)) {
                        $skip = null;
                    }
                } elseif (preg_match('#^<(script|style)\b#i', $chunk, $m) && !str_ends_with($chunk, '/>')) {
                    $skip = strtolower($m[1]);
                } elseif (preg_match('#^<a\b#i', $chunk)) {
                    $inLink++;
                } elseif (preg_match('#^</a\s*>#i', $chunk)) {
                    $inLink = max(0, $inLink - 1);
                }
                $out .= $chunk;
                continue;
            }
            $new = $skip === null ? $visit($chunk, $inLink > 0) : null;
            $out .= $new ?? $chunk;
        }

        return $out;
    }

    /** Text forms of a phrase as it may be stored: raw, and with HTML entities. */
    private static function forms(string $phrase): array
    {
        return array_values(array_unique([$phrase, htmlspecialchars($phrase, ENT_QUOTES | ENT_HTML5, 'UTF-8', false), htmlspecialchars($phrase, ENT_NOQUOTES, 'UTF-8', false)]));
    }

    /** Count matches of $find in visible text; optionally outside links only. */
    public static function countText(string $html, string $find, bool $outsideLinks = false): int
    {
        $count = 0;
        self::mapText($html, function (string $text, bool $inLink) use ($find, $outsideLinks, &$count) {
            if (!($outsideLinks && $inLink)) {
                foreach (self::forms($find) as $form) {
                    $count += substr_count($text, $form);
                    if (substr_count($text, $form)) {
                        break;
                    }
                }
            }

            return null;
        });

        return $count;
    }

    /**
     * Replace matches of $find in visible text with $replacement (raw HTML, already escaped by the caller).
     * $occurrence = only the n-th match (1-based); null = all. Returns [html, replacedCount].
     *
     * @return array{0: string, 1: int}
     */
    public static function replaceText(string $html, string $find, string $replacement, ?int $occurrence = null, bool $outsideLinks = false): array
    {
        $seen = 0;
        $done = 0;
        $out = self::mapText($html, function (string $text, bool $inLink) use ($find, $replacement, $occurrence, $outsideLinks, &$seen, &$done) {
            if ($outsideLinks && $inLink) {
                return null;
            }
            foreach (self::forms($find) as $form) {
                if (!str_contains($text, $form)) {
                    continue;
                }
                $result = '';
                $offset = 0;
                while (($pos = strpos($text, $form, $offset)) !== false) {
                    $seen++;
                    $result .= substr($text, $offset, $pos - $offset);
                    if ($occurrence === null || $seen === $occurrence) {
                        $result .= $replacement;
                        $done++;
                    } else {
                        $result .= $form;
                    }
                    $offset = $pos + strlen($form);
                }

                return $result . substr($text, $offset);
            }

            return null;
        });

        return [$out, $done];
    }

    /** Short text around each match, for previews. */
    public static function snippets(string $html, string $find, int $max = 3): array
    {
        $text = html_entity_decode(strip_tags(preg_replace('#<(script|style)\b.*?</\1>#is', ' ', $html)), ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $text = preg_replace('/\s+/u', ' ', $text);
        $out = [];
        $offset = 0;
        while (count($out) < $max && ($pos = mb_strpos($text, $find, $offset)) !== false) {
            $start = max(0, $pos - 50);
            $out[] = ($start > 0 ? '…' : '') . mb_substr($text, $start, mb_strlen($find) + 100) . '…';
            $offset = $pos + mb_strlen($find);
        }

        return $out;
    }

    /** Same target? Internal links compare as paths, others as lower-cased URLs without trailing slash. */
    public static function sameTarget(string $href, string $target): bool
    {
        $a = SiteCrawler::internalPath($href);
        $b = SiteCrawler::internalPath($target);
        if ($a !== null || $b !== null) {
            return $a === $b;
        }

        return rtrim(strtolower(trim($href)), '/') === rtrim(strtolower(trim($target)), '/');
    }

    /**
     * Rewrite <a> elements whose href matches $target. $fn(string $openTag, string $inner): string returns the replacement.
     *
     * @return array{0: string, 1: int}
     */
    public static function mapLinks(string $html, string $target, callable $fn): array
    {
        $count = 0;
        $out = preg_replace_callback('#(<a\b[^>]*?\bhref\s*=\s*(["\'])(.*?)\2[^>]*>)(.*?)</a\s*>#is', function ($m) use ($target, $fn, &$count) {
            if (!self::sameTarget(html_entity_decode($m[3], ENT_QUOTES | ENT_HTML5), $target)) {
                return $m[0];
            }
            $count++;

            return $fn($m[1], $m[4]);
        }, $html);

        return [$out, $count];
    }

    /** Set an attribute on an opening tag (replacing it when present). */
    public static function setAttr(string $tag, string $name, string $value): string
    {
        $escaped = htmlspecialchars($value, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $pattern = '#(\s' . preg_quote($name, '#') . '\s*=\s*)(["\']).*?\2#is';
        if (preg_match($pattern, $tag)) {
            return preg_replace($pattern, '${1}"' . str_replace(['\\', '$'], ['\\\\', '\\$'], $escaped) . '"', $tag, 1);
        }

        return preg_replace('#\s*(/?)>$#', ' ' . $name . '="' . $escaped . '"$1>', $tag, 1);
    }

    /**
     * Rewrite <img> tags whose src matches $src. $fn(string $tag): string.
     *
     * @return array{0: string, 1: int}
     */
    public static function mapImages(string $html, string $src, callable $fn): array
    {
        $count = 0;
        $out = preg_replace_callback('#<img\b[^>]*>#is', function ($m) use ($src, $fn, &$count) {
            if (!preg_match('#\b(?:data-)?src\s*=\s*(["\'])(.*?)\1#is', $m[0], $s) || !self::sameImage($s[2], $src)) {
                return $m[0];
            }
            $count++;

            return $fn($m[0]);
        }, $html);

        return [$out, $count];
    }

    public static function sameImage(string $a, string $b): bool
    {
        $norm = static function (string $u): string {
            $p = SiteCrawler::internalPath($u) ?? (preg_match('#^(https?:)?//#i', $u) ? strtolower($u) : '/' . ltrim($u, '/'));

            return rawurldecode($p);
        };

        return $norm(trim($a)) === $norm(trim($b));
    }

    /**
     * Rewrite <hN> headings whose text equals $text (case-insensitive, tags ignored).
     * $fn(int $level, string $attrs, string $inner): string.
     *
     * @return array{0: string, 1: int}
     */
    public static function mapHeadings(string $html, string $text, callable $fn): array
    {
        $count = 0;
        $want = mb_strtolower(trim(preg_replace('/\s+/u', ' ', $text)));
        $out = preg_replace_callback('#<h([1-6])\b([^>]*)>(.*?)</h\1\s*>#is', function ($m) use ($want, $fn, &$count) {
            $plain = mb_strtolower(trim(preg_replace('/\s+/u', ' ', html_entity_decode(strip_tags($m[3]), ENT_QUOTES | ENT_HTML5, 'UTF-8'))));
            if ($plain !== $want) {
                return $m[0];
            }
            $count++;

            return $fn((int) $m[1], $m[2], $m[3]);
        }, $html);

        return [$out, $count];
    }
}
