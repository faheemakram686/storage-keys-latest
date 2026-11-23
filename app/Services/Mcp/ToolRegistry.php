<?php

namespace App\Services\Mcp;

use App\Services\Mcp\Tools\AuditTools;
use App\Services\Mcp\Tools\SafetyTools;
use App\Services\Mcp\Tools\SectionTools;
use App\Services\Mcp\Tools\Toolset;

/**
 * The MCP tools added after v1.3. McpStreamController keeps its original tools and asks
 * this registry about every other name.
 */
class ToolRegistry
{
    /** @var list<class-string<Toolset>> */
    private const TOOLSETS = [
        SafetyTools::class,
        SectionTools::class,
        AuditTools::class,
    ];

    /** @return list<Toolset> */
    public function toolsets(): array
    {
        return array_map(static fn (string $class) => app($class), self::TOOLSETS);
    }

    public function definitions(): array
    {
        $defs = [];
        foreach ($this->toolsets() as $set) {
            array_push($defs, ...$set->definitions());
        }

        return $defs;
    }

    public function find(string $name): ?Toolset
    {
        foreach ($this->toolsets() as $set) {
            if ($set->handles($name)) {
                return $set;
            }
        }

        return null;
    }
}
