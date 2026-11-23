<?php

namespace Tests\Feature\Mcp;

use App\Models\Page;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Routing\Middleware\ThrottleRequests;
use Illuminate\Testing\TestResponse;
use Tests\CreatesApplication;

/**
 * Base for MCP tool tests. DatabaseTransactions against the app DB, like Tests\Feature\MultiUnit:
 * Tests\TestCase uses RefreshDatabase, which would wipe the local database.
 */
abstract class McpTestCase extends BaseTestCase
{
    use CreatesApplication;
    use DatabaseTransactions;

    protected string $token = 'test-mcp-token-0123456789';

    protected function setUp(): void
    {
        parent::setUp();
        config(['services.mcp_blog.token' => $this->token]);
        $this->withoutMiddleware(ThrottleRequests::class);
        \App\Services\Mcp\SiteSettings::flush();
    }

    /**
     * Call an MCP tool over the real JSON-RPC endpoint and return [data, isError].
     *
     * @return array{0: array<string, mixed>, 1: bool}
     */
    protected function tool(string $name, array $arguments = []): array
    {
        $response = $this->rpc('tools/call', ['name' => $name, 'arguments' => $arguments]);
        $result = $response->json('result');
        $text = $result['content'][0]['text'] ?? '';
        $data = json_decode($text, true);

        return [is_array($data) ? $data : ['message' => $text], (bool) ($result['isError'] ?? false)];
    }

    /** Call a tool that must succeed; returns its data. */
    protected function ok(string $name, array $arguments = []): array
    {
        [$data, $isError] = $this->tool($name, $arguments);
        $this->assertFalse($isError, $name . ' failed: ' . json_encode($data));

        return $data;
    }

    /** Call a tool that must fail; returns its data. */
    protected function fails(string $name, array $arguments = []): array
    {
        [$data, $isError] = $this->tool($name, $arguments);
        $this->assertTrue($isError, $name . ' should have failed: ' . json_encode($data));

        return $data;
    }

    protected function rpc(string $method, array $params = []): TestResponse
    {
        return $this->postJson('/api/mcp', [
            'jsonrpc' => '2.0',
            'id' => 1,
            'method' => $method,
            'params' => $params,
        ], ['Authorization' => 'Bearer ' . $this->token])->assertOk();
    }

    protected function makePage(array $attrs = []): Page
    {
        return Page::create(array_merge([
            'title' => 'MCP Test Page',
            'slug' => 'mcp-test-page-' . uniqid(),
            'content' => '<section class="sk-section"><h2>Hello</h2><p>Test content for the page.</p></section>',
            'status' => 0,
            'is_deleted' => 0,
            'hide_banner' => 0,
        ], $attrs));
    }
}
