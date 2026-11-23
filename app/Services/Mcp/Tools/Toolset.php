<?php

namespace App\Services\Mcp\Tools;

use App\Services\Mcp\McpException;

/**
 * A group of MCP tools. definitions() feeds tools/list; call() returns the result data
 * (wrapped as {success: true, ...} by the stream controller) or throws McpException.
 */
abstract class Toolset
{
    /** @return list<array<string, mixed>> */
    abstract public function definitions(): array;

    /**
     * @param  array<string, mixed>  $a
     * @return array<string, mixed>
     */
    abstract public function call(string $name, array $a): array;

    public function handles(string $name): bool
    {
        foreach ($this->definitions() as $def) {
            if ($def['name'] === $name) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param  array<string, array<string, mixed>>  $properties
     */
    protected function tool(string $name, string $description, array $properties = [], array $required = []): array
    {
        $schema = ['type' => 'object', 'properties' => $properties === [] ? new \stdClass() : $properties];
        if ($required !== []) {
            $schema['required'] = $required;
        }

        return ['name' => $name, 'description' => $description, 'inputSchema' => $schema];
    }

    protected function str(array $a, string $key, bool $required = false, ?string $default = null): ?string
    {
        $value = $a[$key] ?? null;
        if (is_array($value)) {
            throw new McpException($key . ' must be a string.', 422);
        }
        $value = $value === null ? null : (string) $value;
        if ($required && ($value === null || trim($value) === '')) {
            throw new McpException($key . ' is required.', 422);
        }

        return $value ?? $default;
    }

    protected function int(array $a, string $key, ?int $default = null, bool $required = false, ?int $min = null, ?int $max = null): ?int
    {
        if (!array_key_exists($key, $a) || $a[$key] === null || $a[$key] === '') {
            if ($required) {
                throw new McpException($key . ' is required.', 422);
            }

            return $default;
        }
        if (!is_numeric($a[$key])) {
            throw new McpException($key . ' must be a number.', 422);
        }
        $value = (int) $a[$key];
        if ($min !== null) {
            $value = max($min, $value);
        }
        if ($max !== null) {
            $value = min($max, $value);
        }

        return $value;
    }

    protected function bool(array $a, string $key, bool $default = false): bool
    {
        if (!array_key_exists($key, $a) || $a[$key] === null || $a[$key] === '') {
            return $default;
        }
        $value = $a[$key];
        if (is_bool($value)) {
            return $value;
        }

        return filter_var($value, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE) ?? $default;
    }

    protected function arr(array $a, string $key, bool $required = false): array
    {
        $value = $a[$key] ?? null;
        if (is_string($value) && $value !== '' && in_array($value[0], ['[', '{'], true)) {
            $value = json_decode($value, true);
        }
        if ($value === null) {
            if ($required) {
                throw new McpException($key . ' is required.', 422);
            }

            return [];
        }
        if (!is_array($value)) {
            throw new McpException($key . ' must be a list.', 422);
        }

        return $value;
    }

    protected function requireConfirm(array $a, string $what): void
    {
        if (!$this->bool($a, 'confirm')) {
            throw new McpException($what . ' requires confirm=true.', 422);
        }
    }
}
