<?php

namespace App\Services\Mcp;

/**
 * A refused or failed MCP tool call. The stream controller turns it into an isError result
 * shaped like the REST tools: {success: false, message, ...extra}.
 */
class McpException extends \RuntimeException
{
    public function __construct(string $message, public int $status = 422, public array $extra = [])
    {
        parent::__construct($message);
    }

    public static function notFound(string $what): self
    {
        return new self($what . ' not found.', 404);
    }

    public function payload(): array
    {
        return array_merge(['success' => false, 'message' => $this->getMessage()], $this->extra);
    }
}
