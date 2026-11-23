<?php

namespace App\Services\Mcp;

/**
 * Who is changing content right now. McpStreamController sets the tool name per call,
 * so every revision row says which MCP tool made the change.
 */
class McpContext
{
    public static ?string $tool = null;

    public static ?string $source = null;

    public static function source(): string
    {
        if (self::$source !== null) {
            return self::$source;
        }
        if (app()->runningInConsole()) {
            return 'console';
        }

        return request()->is('api/mcp*') ? 'mcp' : 'admin';
    }

    /**
     * Run $fn as the given MCP tool, then restore the previous context.
     *
     * @template T
     * @param  callable(): T  $fn
     * @return T
     */
    public static function as(string $tool, callable $fn)
    {
        [$prevTool, $prevSource] = [self::$tool, self::$source];
        self::$tool = $tool;
        self::$source = 'mcp';
        try {
            return $fn();
        } finally {
            [self::$tool, self::$source] = [$prevTool, $prevSource];
        }
    }
}
