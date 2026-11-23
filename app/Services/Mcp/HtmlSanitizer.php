<?php

namespace App\Services\Mcp;

/**
 * Removes what must never come in through the MCP tools, keeps everything the site's pages use
 * (<section>, <style>, classes, data-* attributes, SVG, JSON-LD).
 *
 * Removed: <script> except type="application/ld+json", event handler attributes (on*),
 * <form> and form controls, <object>/<embed>/<applet>/<base>/<meta>/<link>/<frame>,
 * iframes not from Google Maps, YouTube or Vimeo, javascript:/vbscript: URLs, data: URLs
 * outside <img>, and CSS expressions.
 *
 * Input with nothing to remove is returned byte for byte, so clean content is never reformatted.
 */
class HtmlSanitizer
{
    private const DROP_TAGS = ['form', 'input', 'button', 'select', 'textarea', 'object', 'embed', 'applet', 'base', 'meta', 'link', 'frame', 'frameset'];

    private const URL_ATTRS = ['href', 'src', 'action', 'formaction', 'xlink:href', 'poster', 'background', 'srcset', 'data'];

    private const IFRAME_HOSTS = ['www.google.com', 'google.com', 'maps.google.com', 'www.youtube.com', 'youtube.com', 'www.youtube-nocookie.com', 'player.vimeo.com'];

    /**
     * @return array{html: string, removed: list<string>}
     */
    public static function clean(string $html): array
    {
        if (trim($html) === '') {
            return ['html' => $html, 'removed' => []];
        }

        $doc = new \DOMDocument();
        $prev = libxml_use_internal_errors(true);
        $doc->loadHTML('<?xml encoding="utf-8"?><div id="__sk_root">' . $html . '</div>', LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
        libxml_clear_errors();
        libxml_use_internal_errors($prev);

        $root = $doc->getElementById('__sk_root');
        if (!$root) {
            return ['html' => $html, 'removed' => []];
        }

        $removed = [];
        $xpath = new \DOMXPath($doc);

        foreach (iterator_to_array($xpath->query('.//*', $root)) as $el) {
            /** @var \DOMElement $el */
            if (!$el->parentNode) {
                continue; // inside an element removed earlier
            }
            $tag = strtolower($el->nodeName);

            if ($tag === 'script') {
                if (strtolower(trim($el->getAttribute('type'))) !== 'application/ld+json') {
                    $removed[] = '<script> element';
                    $el->parentNode->removeChild($el);
                    continue;
                }
            }
            if (in_array($tag, self::DROP_TAGS, true)) {
                $removed[] = '<' . $tag . '> element';
                $el->parentNode->removeChild($el);
                continue;
            }
            if ($tag === 'iframe' && !self::allowedIframe($el->getAttribute('src'))) {
                $removed[] = '<iframe> from ' . (parse_url($el->getAttribute('src'), PHP_URL_HOST) ?: 'unknown source');
                $el->parentNode->removeChild($el);
                continue;
            }

            foreach (iterator_to_array($el->attributes) as $attr) {
                $name = strtolower($attr->nodeName);
                $value = $attr->nodeValue;
                if (str_starts_with($name, 'on')) {
                    $removed[] = $name . ' attribute on <' . $tag . '>';
                    $el->removeAttribute($attr->nodeName);
                } elseif (in_array($name, self::URL_ATTRS, true) && self::badUrl($value, $tag)) {
                    $removed[] = 'unsafe ' . $name . ' URL on <' . $tag . '>';
                    $el->removeAttribute($attr->nodeName);
                } elseif ($name === 'style' && preg_match('/expression\s*\(|javascript:|behavior\s*:/i', $value)) {
                    $removed[] = 'unsafe style on <' . $tag . '>';
                    $el->removeAttribute($attr->nodeName);
                } elseif ($name === 'srcdoc') {
                    $removed[] = 'srcdoc attribute on <' . $tag . '>';
                    $el->removeAttribute($attr->nodeName);
                }
            }
        }

        if ($removed === []) {
            return ['html' => $html, 'removed' => []];
        }

        $out = '';
        foreach ($root->childNodes as $child) {
            $out .= $doc->saveHTML($child);
        }

        return ['html' => $out, 'removed' => array_values(array_unique($removed))];
    }

    private static function badUrl(string $value, string $tag): bool
    {
        $v = strtolower(preg_replace('/[\x00-\x20]+/', '', html_entity_decode($value, ENT_QUOTES | ENT_HTML5)));
        if (str_starts_with($v, 'javascript:') || str_starts_with($v, 'vbscript:')) {
            return true;
        }
        if (str_starts_with($v, 'data:')) {
            return !($tag === 'img' && str_starts_with($v, 'data:image/') && !str_starts_with($v, 'data:image/svg'));
        }

        return false;
    }

    private static function allowedIframe(string $src): bool
    {
        $host = strtolower((string) parse_url($src, PHP_URL_HOST));
        if (!in_array($host, self::IFRAME_HOSTS, true)) {
            return false;
        }

        return !str_contains($host, 'google') || str_contains(strtolower($src), '/maps');
    }
}
