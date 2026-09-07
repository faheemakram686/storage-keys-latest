<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Streamable HTTP MCP endpoint for Claude.ai custom connectors.
 * Protocol: https://modelcontextprotocol.io/specification/2025-03-26/basic/transports
 *
 * Phase 1 adds blog CRUD + site/media tools without removing create_blog / list_blogs.
 */
class McpStreamController extends Controller
{
    private const PROTOCOL_VERSION = '2025-03-26';

    public function handle(Request $request)
    {
        if ($request->isMethod('DELETE')) {
            return response('', 405);
        }

        if ($request->isMethod('GET')) {
            return response('Method Not Allowed', 405);
        }

        $payload = $request->json()->all();
        if ($payload === []) {
            return response()->json([
                'jsonrpc' => '2.0',
                'error' => ['code' => -32700, 'message' => 'Parse error'],
                'id' => null,
            ], 400);
        }

        if (array_is_list($payload)) {
            $hasRequest = false;
            $responses = [];

            foreach ($payload as $message) {
                if (!is_array($message)) {
                    continue;
                }
                $result = $this->dispatchMessage($message);
                if ($result === null) {
                    continue;
                }
                $hasRequest = true;
                $responses[] = $result;
            }

            if (!$hasRequest) {
                return response('', 202);
            }

            return response()->json(count($responses) === 1 ? $responses[0] : $responses);
        }

        $result = $this->dispatchMessage($payload);
        if ($result === null) {
            return response('', 202);
        }

        return response()->json($result);
    }

    /**
     * @return array<string, mixed>|null
     */
    private function dispatchMessage(array $message): ?array
    {
        $id = $message['id'] ?? null;
        $method = $message['method'] ?? null;

        if ($method === null && array_key_exists('result', $message)) {
            return null;
        }

        if (!is_string($method) || $method === '') {
            return $this->error($id, -32600, 'Invalid Request');
        }

        $isNotification = !array_key_exists('id', $message);

        try {
            switch ($method) {
                case 'initialize':
                    return $this->rpcResult($id, [
                        'protocolVersion' => self::PROTOCOL_VERSION,
                        'capabilities' => [
                            'tools' => new \stdClass(),
                        ],
                        'serverInfo' => [
                            'name' => 'storagekeys-blog',
                            'version' => '1.2.0',
                        ],
                        'instructions' => 'StorageKeys blog MCP v1.2. Prefer drafts (status=0). Phase 1: CRUD + site/media. Phase 2: get_seo_meta/update_seo_meta and get_schema/update_schema (nullable SEO fields; empty falls back to title/excerpt on the site). Only publish when asked.',
                    ]);

                case 'notifications/initialized':
                case 'notifications/cancelled':
                    return null;

                case 'ping':
                    return $this->rpcResult($id, new \stdClass());

                case 'tools/list':
                    return $this->rpcResult($id, [
                        'tools' => $this->toolDefinitions(),
                    ]);

                case 'tools/call':
                    $params = is_array($message['params'] ?? null) ? $message['params'] : [];
                    $name = $params['name'] ?? '';
                    $arguments = is_array($params['arguments'] ?? null) ? $params['arguments'] : [];

                    return $this->rpcResult($id, $this->callTool((string) $name, $arguments));

                default:
                    if ($isNotification) {
                        return null;
                    }

                    return $this->error($id, -32601, 'Method not found: ' . $method);
            }
        } catch (\Throwable $e) {
            if ($isNotification) {
                return null;
            }

            return $this->rpcResult($id, [
                'content' => [
                    [
                        'type' => 'text',
                        'text' => 'Error: ' . $e->getMessage(),
                    ],
                ],
                'isError' => true,
            ]);
        }
    }

    private function toolDefinitions(): array
    {
        return [
            [
                'name' => 'list_blogs',
                'description' => 'List recent StorageKeys blogs from the database (newest first).',
                'inputSchema' => [
                    'type' => 'object',
                    'properties' => [
                        'limit' => [
                            'type' => 'integer',
                            'minimum' => 1,
                            'maximum' => 50,
                            'description' => 'How many blogs to return (default 10)',
                        ],
                    ],
                ],
            ],
            [
                'name' => 'create_blog',
                'description' => 'Create a blog post. Defaults to draft (status=0) unless status=1 is passed.',
                'inputSchema' => [
                    'type' => 'object',
                    'properties' => [
                        'title' => ['type' => 'string', 'minLength' => 3, 'maxLength' => 255],
                        'description' => ['type' => 'string', 'minLength' => 20, 'description' => 'HTML or text body'],
                        'status' => ['type' => 'integer', 'enum' => [0, 1]],
                        'image_url' => ['type' => 'string', 'format' => 'uri'],
                        'slug' => ['type' => 'string'],
                    ],
                    'required' => ['title', 'description'],
                ],
            ],
            [
                'name' => 'get_blog',
                'description' => 'Get one blog by numeric id or slug (includes full description).',
                'inputSchema' => [
                    'type' => 'object',
                    'properties' => [
                        'id' => ['type' => 'integer', 'description' => 'Blog id'],
                        'slug' => ['type' => 'string', 'description' => 'Blog slug'],
                    ],
                ],
            ],
            [
                'name' => 'update_blog',
                'description' => 'Update an existing blog by id or slug. Only send fields to change.',
                'inputSchema' => [
                    'type' => 'object',
                    'properties' => [
                        'id' => ['type' => 'integer'],
                        'slug' => ['type' => 'string'],
                        'title' => ['type' => 'string'],
                        'description' => ['type' => 'string'],
                        'status' => ['type' => 'integer', 'enum' => [0, 1]],
                        'image_url' => ['type' => 'string', 'format' => 'uri'],
                        'new_slug' => ['type' => 'string', 'description' => 'Optional new slug'],
                    ],
                ],
            ],
            [
                'name' => 'delete_blog',
                'description' => 'Soft-delete a blog (is_deleted=1). Requires confirm=true.',
                'inputSchema' => [
                    'type' => 'object',
                    'properties' => [
                        'id' => ['type' => 'integer'],
                        'slug' => ['type' => 'string'],
                        'confirm' => ['type' => 'boolean', 'description' => 'Must be true'],
                    ],
                    'required' => ['confirm'],
                ],
            ],
            [
                'name' => 'bulk_update_posts',
                'description' => 'Bulk update status for multiple blog ids. Requires confirm=true.',
                'inputSchema' => [
                    'type' => 'object',
                    'properties' => [
                        'ids' => [
                            'type' => 'array',
                            'items' => ['type' => 'integer'],
                            'minItems' => 1,
                            'maxItems' => 50,
                        ],
                        'status' => ['type' => 'integer', 'enum' => [0, 1]],
                        'confirm' => ['type' => 'boolean'],
                    ],
                    'required' => ['ids', 'status', 'confirm'],
                ],
            ],
            [
                'name' => 'search_content',
                'description' => 'Search blogs by title, slug, or description text.',
                'inputSchema' => [
                    'type' => 'object',
                    'properties' => [
                        'q' => ['type' => 'string', 'minLength' => 2],
                        'limit' => ['type' => 'integer', 'minimum' => 1, 'maximum' => 50],
                    ],
                    'required' => ['q'],
                ],
            ],
            [
                'name' => 'get_site_info',
                'description' => 'Base URL, timezone, blog permalink pattern, sitemap URL.',
                'inputSchema' => ['type' => 'object', 'properties' => new \stdClass()],
            ],
            [
                'name' => 'get_pages',
                'description' => 'List known static marketing page URLs.',
                'inputSchema' => ['type' => 'object', 'properties' => new \stdClass()],
            ],
            [
                'name' => 'get_sitemap',
                'description' => 'JSON sitemap entries (static pages + blogs) plus sitemap.xml URL.',
                'inputSchema' => ['type' => 'object', 'properties' => new \stdClass()],
            ],
            [
                'name' => 'list_media',
                'description' => 'List recent files in storage/uploads/blog-images.',
                'inputSchema' => [
                    'type' => 'object',
                    'properties' => [
                        'limit' => ['type' => 'integer', 'minimum' => 1, 'maximum' => 100],
                    ],
                ],
            ],
            [
                'name' => 'upload_media',
                'description' => 'Download an image from a public URL into blog-images and return the stored URL.',
                'inputSchema' => [
                    'type' => 'object',
                    'properties' => [
                        'image_url' => ['type' => 'string', 'format' => 'uri'],
                    ],
                    'required' => ['image_url'],
                ],
            ],
            [
                'name' => 'get_seo_meta',
                'description' => 'Get SEO meta for a blog (stored + resolved fallbacks).',
                'inputSchema' => [
                    'type' => 'object',
                    'properties' => [
                        'id' => ['type' => 'integer'],
                        'slug' => ['type' => 'string'],
                    ],
                ],
            ],
            [
                'name' => 'update_seo_meta',
                'description' => 'Update SEO meta fields (meta_title, meta_description, canonical_url, robots). Empty string clears.',
                'inputSchema' => [
                    'type' => 'object',
                    'properties' => [
                        'id' => ['type' => 'integer'],
                        'slug' => ['type' => 'string'],
                        'meta_title' => ['type' => 'string'],
                        'meta_description' => ['type' => 'string'],
                        'canonical_url' => ['type' => 'string', 'format' => 'uri'],
                        'robots' => ['type' => 'string', 'description' => 'e.g. index,follow or noindex'],
                    ],
                ],
            ],
            [
                'name' => 'get_schema',
                'description' => 'Get JSON-LD schema stored for a blog (null if none).',
                'inputSchema' => [
                    'type' => 'object',
                    'properties' => [
                        'id' => ['type' => 'integer'],
                        'slug' => ['type' => 'string'],
                    ],
                ],
            ],
            [
                'name' => 'update_schema',
                'description' => 'Set JSON-LD schema object for a blog. Pass schema=null to clear.',
                'inputSchema' => [
                    'type' => 'object',
                    'properties' => [
                        'id' => ['type' => 'integer'],
                        'slug' => ['type' => 'string'],
                        'schema' => [
                            'description' => 'JSON-LD object/array, or null to clear',
                        ],
                        'schema_json' => [
                            'type' => 'string',
                            'description' => 'Alternative: raw JSON string',
                        ],
                    ],
                ],
            ],
        ];
    }

    /**
     * @param  array<string, mixed>  $arguments
     * @return array{content: list<array{type: string, text: string}>, isError?: bool}
     */
    private function callTool(string $name, array $arguments): array
    {
        $arguments = $this->normalizeToolArguments($arguments);
        $blogs = app(McpBlogController::class);
        $site = app(McpSiteController::class);

        switch ($name) {
            case 'list_blogs':
                return $this->fromResponse($blogs->index($this->jsonRequest('GET', '/api/mcp/blogs', [
                    'limit' => $arguments['limit'] ?? 10,
                ])));

            case 'create_blog':
                $payload = $this->createPayload($arguments);

                return $this->fromResponse($blogs->store($this->jsonRequest('POST', '/api/mcp/blogs', $payload)));

            case 'get_blog':
                $key = $this->blogKey($arguments);
                if ($key === null) {
                    return $this->toolError('Provide id or slug.');
                }

                return $this->fromResponse($blogs->show($this->jsonRequest('GET', '/api/mcp/blogs/' . $key), $key));

            case 'update_blog':
                $key = $this->blogKey($arguments);
                if ($key === null) {
                    return $this->toolError('Provide id or slug.');
                }
                $payload = [];
                foreach (['title', 'description', 'status', 'image_url'] as $field) {
                    if (array_key_exists($field, $arguments)) {
                        $payload[$field] = $arguments[$field];
                    }
                }
                if (!empty($arguments['new_slug'])) {
                    $payload['slug'] = $arguments['new_slug'];
                }

                return $this->fromResponse($blogs->update($this->jsonRequest('PATCH', '/api/mcp/blogs/' . $key, $payload), $key));

            case 'delete_blog':
                $key = $this->blogKey($arguments);
                if ($key === null) {
                    return $this->toolError('Provide id or slug.');
                }

                return $this->fromResponse($blogs->destroy($this->jsonRequest('DELETE', '/api/mcp/blogs/' . $key, [
                    'confirm' => $arguments['confirm'] ?? false,
                ]), $key));

            case 'bulk_update_posts':
                return $this->fromResponse($blogs->bulkUpdate($this->jsonRequest('POST', '/api/mcp/blogs/bulk-update', [
                    'ids' => $arguments['ids'] ?? [],
                    'status' => $arguments['status'] ?? null,
                    'confirm' => $arguments['confirm'] ?? false,
                ])));

            case 'search_content':
                return $this->fromResponse($blogs->search($this->jsonRequest('GET', '/api/mcp/blogs/search', [
                    'q' => $arguments['q'] ?? '',
                    'limit' => $arguments['limit'] ?? 10,
                ])));

            case 'get_site_info':
                return $this->fromResponse($site->siteInfo());

            case 'get_pages':
                return $this->fromResponse($site->pages());

            case 'get_sitemap':
                return $this->fromResponse($site->sitemap());

            case 'list_media':
                return $this->fromResponse($site->listMedia($this->jsonRequest('GET', '/api/mcp/media', [
                    'limit' => $arguments['limit'] ?? 30,
                ])));

            case 'upload_media':
                return $this->fromResponse($site->uploadMedia($this->jsonRequest('POST', '/api/mcp/media', [
                    'image_url' => $arguments['image_url'] ?? '',
                ])));

            case 'get_seo_meta':
                $key = $this->blogKey($arguments);
                if ($key === null) {
                    return $this->toolError('Provide id or slug.');
                }

                return $this->fromResponse($blogs->seoMeta($this->jsonRequest('GET', '/api/mcp/blogs/' . $key . '/seo'), $key));

            case 'update_seo_meta':
                $key = $this->blogKey($arguments);
                if ($key === null) {
                    return $this->toolError('Provide id or slug.');
                }
                $payload = [];
                foreach (['meta_title', 'meta_description', 'canonical_url', 'robots'] as $field) {
                    if (array_key_exists($field, $arguments)) {
                        $payload[$field] = $arguments[$field];
                    }
                }

                return $this->fromResponse($blogs->updateSeoMeta($this->jsonRequest('PATCH', '/api/mcp/blogs/' . $key . '/seo', $payload), $key));

            case 'get_schema':
                $key = $this->blogKey($arguments);
                if ($key === null) {
                    return $this->toolError('Provide id or slug.');
                }

                return $this->fromResponse($blogs->schema($this->jsonRequest('GET', '/api/mcp/blogs/' . $key . '/schema'), $key));

            case 'update_schema':
                $key = $this->blogKey($arguments);
                if ($key === null) {
                    return $this->toolError('Provide id or slug.');
                }
                $payload = [];
                if (array_key_exists('schema', $arguments)) {
                    $payload['schema'] = $arguments['schema'];
                }
                if (array_key_exists('schema_json', $arguments)) {
                    $payload['schema_json'] = $arguments['schema_json'];
                }

                return $this->fromResponse($blogs->updateSchema($this->jsonRequest('PATCH', '/api/mcp/blogs/' . $key . '/schema', $payload), $key));

            default:
                return $this->toolError('Unknown tool: ' . $name);
        }
    }

    /**
     * @param  array<string, mixed>  $arguments
     * @return array{title: string, description: string, status?: int, image_url?: string, slug?: string}
     */
    private function createPayload(array $arguments): array
    {
        $title = trim((string) ($arguments['title'] ?? $arguments['name'] ?? ''));
        $description = $arguments['description'] ?? $arguments['content'] ?? $arguments['body'] ?? '';
        if (is_array($description)) {
            $description = json_encode($description, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        }
        $description = trim((string) $description);

        $payload = [
            'title' => $title,
            'description' => $description,
        ];
        if (array_key_exists('status', $arguments) && in_array($arguments['status'], [0, 1, '0', '1'], true)) {
            $payload['status'] = (int) $arguments['status'];
        }
        if (!empty($arguments['image_url']) && is_string($arguments['image_url'])) {
            $payload['image_url'] = $arguments['image_url'];
        }
        if (!empty($arguments['slug']) && is_string($arguments['slug'])) {
            $payload['slug'] = $arguments['slug'];
        }

        return $payload;
    }

    /**
     * @param  array<string, mixed>  $arguments
     */
    private function blogKey(array $arguments): ?string
    {
        if (isset($arguments['id']) && $arguments['id'] !== '' && $arguments['id'] !== null) {
            return (string) $arguments['id'];
        }
        if (!empty($arguments['slug']) && is_string($arguments['slug'])) {
            return $arguments['slug'];
        }

        return null;
    }

    /**
     * @param  array<string, mixed>  $queryOrJson
     */
    private function jsonRequest(string $method, string $uri, array $queryOrJson = []): Request
    {
        if (strtoupper($method) === 'GET' || strtoupper($method) === 'DELETE') {
            $request = Request::create($uri, $method, $queryOrJson);
            $request->headers->set('Accept', 'application/json');

            return $request;
        }

        return Request::create(
            $uri,
            $method,
            [],
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
                'HTTP_ACCEPT' => 'application/json',
            ],
            json_encode($queryOrJson, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)
        );
    }

    private function fromResponse(JsonResponse $response): array
    {
        $data = $response->getData(true);
        $status = $response->getStatusCode();

        return [
            'content' => [
                [
                    'type' => 'text',
                    'text' => json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES),
                ],
            ],
            'isError' => $status >= 400,
        ];
    }

    private function toolError(string $message): array
    {
        return [
            'content' => [
                [
                    'type' => 'text',
                    'text' => $message,
                ],
            ],
            'isError' => true,
        ];
    }

    /**
     * @param  array<string, mixed>  $arguments
     * @return array<string, mixed>
     */
    private function normalizeToolArguments(array $arguments): array
    {
        if (isset($arguments['arguments']) && is_array($arguments['arguments'])) {
            $arguments = array_merge($arguments, $arguments['arguments']);
        }

        if (isset($arguments['description']) && is_string($arguments['description'])) {
            $trimmed = trim($arguments['description']);
            if ($trimmed !== '' && ($trimmed[0] === '{' || $trimmed[0] === '[')) {
                $decoded = json_decode($trimmed, true);
                if (is_array($decoded) && isset($decoded['title'], $decoded['description'])) {
                    return $decoded;
                }
            }
        }

        return $arguments;
    }

    /**
     * @param  mixed  $id
     * @param  mixed  $result
     * @return array<string, mixed>
     */
    private function rpcResult($id, $result): array
    {
        return [
            'jsonrpc' => '2.0',
            'id' => $id,
            'result' => $result,
        ];
    }

    /**
     * @param  mixed  $id
     * @return array<string, mixed>
     */
    private function error($id, int $code, string $message): array
    {
        return [
            'jsonrpc' => '2.0',
            'id' => $id,
            'error' => [
                'code' => $code,
                'message' => $message,
            ],
        ];
    }
}
