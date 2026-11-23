<?php

namespace App\Services\Mcp;

/**
 * Read-only HTML helpers shared by outline, audit and link tools.
 */
class Html
{
    public static function dom(string $html): \DOMXPath
    {
        $doc = new \DOMDocument();
        $prev = libxml_use_internal_errors(true);
        if (preg_match('/<html[\s>]/i', $html)) {
            // A full document (rendered page): load as is; the body plays the root.
            $doc->loadHTML('<?xml encoding="utf-8"?>' . $html);
            $body = $doc->getElementsByTagName('body')->item(0);
            if ($body instanceof \DOMElement) {
                $body->setAttribute('id', '__sk_root');
                $body->setIdAttribute('id', true);
            }
        } else {
            $doc->loadHTML('<?xml encoding="utf-8"?><div id="__sk_root">' . $html . '</div>', LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
        }
        libxml_clear_errors();
        libxml_use_internal_errors($prev);

        return new \DOMXPath($doc);
    }

    public static function text(\DOMNode $node): string
    {
        return trim(preg_replace('/\s+/u', ' ', $node->textContent));
    }

    /** @return list<array{level: int, text: string}> */
    public static function headings(string $html): array
    {
        $out = [];
        foreach (self::dom($html)->query('//h1|//h2|//h3|//h4|//h5|//h6') as $h) {
            $out[] = ['level' => (int) substr($h->nodeName, 1), 'text' => self::text($h)];
        }

        return $out;
    }

    /** Visible words: no script, style, noscript or template content. */
    public static function wordCount(string $html): int
    {
        $xp = self::dom($html);
        foreach (iterator_to_array($xp->query('//script|//style|//noscript|//template|//svg')) as $n) {
            $n->parentNode->removeChild($n);
        }
        $root = $xp->document->getElementById('__sk_root');
        $text = $root ? $root->textContent : '';
        preg_match_all('/[\p{L}\p{N}][\p{L}\p{N}\'’-]*/u', $text, $m);

        return count($m[0]);
    }

    /** @return list<array{href: string, text: string}> */
    public static function links(string $html): array
    {
        $out = [];
        foreach (self::dom($html)->query('//a[@href]') as $a) {
            $out[] = ['href' => trim($a->getAttribute('href')), 'text' => self::text($a)];
        }

        return $out;
    }

    /** @return list<array{src: string, alt: ?string}> */
    public static function images(string $html): array
    {
        $out = [];
        foreach (self::dom($html)->query('//img') as $img) {
            $out[] = [
                'src' => trim($img->getAttribute('src') ?: $img->getAttribute('data-src')),
                'alt' => $img->hasAttribute('alt') ? trim($img->getAttribute('alt')) : null,
            ];
        }

        return $out;
    }

    /** <title> and meta description from a full HTML document. */
    public static function meta(string $html): array
    {
        $xp = self::dom($html);
        $title = $xp->query('//title')->item(0);
        $desc = $xp->query('//meta[@name="description"]')->item(0);
        $robots = $xp->query('//meta[@name="robots"]')->item(0);
        $canonical = $xp->query('//link[@rel="canonical"]')->item(0);

        return [
            'title' => $title ? self::text($title) : null,
            'description' => $desc ? trim($desc->getAttribute('content')) : null,
            'robots' => $robots ? trim($robots->getAttribute('content')) : null,
            'canonical' => $canonical ? trim($canonical->getAttribute('href')) : null,
        ];
    }

    /** Decoded JSON-LD blocks, flattened out of @graph. @return list<array> */
    public static function schemaNodes(string $html): array
    {
        $nodes = [];
        foreach (self::dom($html)->query('//script[@type="application/ld+json"]') as $s) {
            $data = json_decode($s->textContent, true);
            if (!is_array($data)) {
                continue;
            }
            foreach (isset($data['@graph']) ? (array) $data['@graph'] : (array_is_list($data) ? $data : [$data]) as $node) {
                if (is_array($node)) {
                    $nodes[] = $node;
                }
            }
        }

        return $nodes;
    }
}
