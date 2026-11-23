<?php

namespace App\Services\Mcp;

use Illuminate\Support\Facades\Log;

/**
 * Components a page section can hold. They are stored in pages.content as a marker
 * (<!--sk-component:name {json attrs}-->) and rendered at display time by ContentRenderer,
 * so forms get a fresh CSRF token and shared partials stay in one place.
 */
class Components
{
    public static function definitions(): array
    {
        return [
            'quote_form' => [
                'description' => 'The site\'s free-quote form (same as on the service pages). Sends an inquiry to the sales team.',
                'view' => 'ui.partials.inquiry-form',
                'attrs' => [
                    'variant' => ['type' => 'string', 'enum' => ['hero', 'simple', 'business', 'warehouse', 'contact'], 'description' => 'Which fields show. Default hero.', 'var' => 'variant'],
                    'title' => ['type' => 'string', 'description' => 'Form heading. Default "Get a Free Quote".', 'var' => 'title'],
                    'subtitle' => ['type' => 'string', 'var' => 'subtitle'],
                    'submit_label' => ['type' => 'string', 'description' => 'Button text.', 'var' => 'submitLabel'],
                    'default_storage' => ['type' => 'string', 'description' => 'Preselected storage type, e.g. "Personal Storage".', 'var' => 'defaultStorage'],
                    'source' => ['type' => 'string', 'description' => 'Lead source label saved with the inquiry.', 'var' => 'source'],
                ],
            ],
            'explore_more' => [
                'description' => 'The "Explore More" internal-links block (related services and guides) used above the FAQ on service pages.',
                'view' => 'ui.partials.explore-more',
                'attrs' => [
                    'page' => ['type' => 'string', 'description' => 'Which page\'s link set to show, e.g. "personal-storage" or "self-storage-dubai".', 'var' => 'page', 'required' => true],
                ],
            ],
        ];
    }

    public static function get(string $name): array
    {
        $defs = self::definitions();
        if (!isset($defs[$name])) {
            throw new McpException('Unknown component "' . $name . '". Use list_components.', 422, ['components' => array_keys($defs)]);
        }

        return $defs[$name];
    }

    /**
     * Keep only known attributes; refuse unknown ones and bad enum values.
     */
    public static function validateAttrs(string $name, array $attrs): array
    {
        $def = self::get($name);
        $clean = [];
        foreach ($attrs as $key => $value) {
            if (!isset($def['attrs'][$key])) {
                throw new McpException('Component ' . $name . ' has no attribute "' . $key . '".', 422, ['attrs' => array_keys($def['attrs'])]);
            }
            if (!is_scalar($value)) {
                throw new McpException('Attribute ' . $key . ' must be text.', 422);
            }
            $value = trim(strip_tags((string) $value));
            $enum = $def['attrs'][$key]['enum'] ?? null;
            if ($enum && !in_array($value, $enum, true)) {
                throw new McpException('Attribute ' . $key . ' must be one of: ' . implode(', ', $enum) . '.', 422);
            }
            $clean[$key] = $value;
        }
        foreach ($def['attrs'] as $key => $spec) {
            if (!empty($spec['required']) && ($clean[$key] ?? '') === '') {
                throw new McpException('Component ' . $name . ' needs attribute "' . $key . '".', 422);
            }
        }

        return $clean;
    }

    public static function marker(string $name, array $attrs): string
    {
        return '<!--sk-component:' . $name . ' ' . json_encode((object) $attrs, JSON_HEX_TAG | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) . '-->';
    }

    public static function render(string $name, array $attrs): string
    {
        $defs = self::definitions();
        if (!isset($defs[$name])) {
            return '';
        }
        $vars = [];
        foreach ($attrs as $key => $value) {
            if (isset($defs[$name]['attrs'][$key])) {
                $vars[$defs[$name]['attrs'][$key]['var']] = $value;
            }
        }

        try {
            return view($defs[$name]['view'], $vars)->render();
        } catch (\Throwable $e) {
            Log::warning('MCP component render failed', ['component' => $name, 'error' => $e->getMessage()]);

            return '';
        }
    }
}
