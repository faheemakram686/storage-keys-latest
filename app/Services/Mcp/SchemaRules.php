<?php

namespace App\Services\Mcp;

/**
 * Rules for JSON-LD saved through MCP:
 * - one node per @type on a page;
 * - no Review or AggregateRating for StorageKeys itself (self-serving review markup breaks Google's rules).
 */
class SchemaRules
{
    private const FORBIDDEN_TYPES = ['Review', 'AggregateRating', 'CriticReview', 'EmployerAggregateRating', 'UserReview'];
    private const FORBIDDEN_PROPS = ['aggregateRating', 'review', 'reviews'];

    /** Flatten @graph / lists into nodes. */
    public static function nodes($schema): array
    {
        if (!is_array($schema)) {
            return [];
        }
        if (isset($schema['@graph'])) {
            return array_values(array_filter((array) $schema['@graph'], 'is_array'));
        }
        if (array_is_list($schema)) {
            $out = [];
            foreach ($schema as $item) {
                array_push($out, ...self::nodes($item));
            }

            return $out;
        }

        return [$schema];
    }

    public static function typeOf(array $node): string
    {
        $type = $node['@type'] ?? '';

        return is_array($type) ? implode('/', $type) : (string) $type;
    }

    /** @return list<string> */
    public static function problems($schema): array
    {
        $problems = [];
        $seen = [];
        foreach (self::nodes($schema) as $node) {
            $type = self::typeOf($node);
            if ($type === '') {
                $problems[] = 'A schema block has no @type.';
                continue;
            }
            if (isset($seen[$type])) {
                $problems[] = 'Two blocks of @type ' . $type . '; a page has one per type.';
            }
            $seen[$type] = true;
            self::walk($node, $problems);
        }

        return array_values(array_unique($problems));
    }

    private static function walk(array $node, array &$problems): void
    {
        foreach ((array) ($node['@type'] ?? []) as $t) {
            if (in_array($t, self::FORBIDDEN_TYPES, true)) {
                $problems[] = $t . ' markup for StorageKeys is not allowed (self-serving reviews and ratings break Google\'s structured data rules).';
            }
        }
        foreach ($node as $key => $value) {
            if (in_array($key, self::FORBIDDEN_PROPS, true)) {
                $problems[] = 'The "' . $key . '" property is not allowed: StorageKeys may not mark up its own reviews or ratings.';
            }
            if (is_array($value)) {
                foreach (array_is_list($value) ? $value : [$value] as $child) {
                    if (is_array($child)) {
                        self::walk($child, $problems);
                    }
                }
            }
        }
    }
}
