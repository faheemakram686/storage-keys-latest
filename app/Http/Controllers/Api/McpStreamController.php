<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

/**
 * Streamable HTTP MCP endpoint for Claude.ai custom connectors.
 * Protocol: https://modelcontextprotocol.io/specification/2025-03-26/basic/transports
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
            // Stateless server: no standalone SSE listen stream.
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

        // Batch: array of messages
        if (array_is_list($payload)) {
            $hasRequest = false;
            $responses = [];

            foreach ($payload as $message) {
                if (!is_array($message)) {
                    continue;
                }
                $result = $this->dispatchMessage($message, $request);
                if ($result === null) {
                    continue; // notification
                }
                $hasRequest = true;
                $responses[] = $result;
            }

            if (!$hasRequest) {
                return response('', 202);
            }

            return response()->json(count($responses) === 1 ? $responses[0] : $responses);
        }

        $result = $this->dispatchMessage($payload, $request);
        if ($result === null) {
            return response('', 202);
        }

        return response()->json($result);
    }

    /**
     * @return array<string, mixed>|null null = notification accepted
     */
    private function dispatchMessage(array $message, Request $request): ?array
    {
        $id = $message['id'] ?? null;
        $method = $message['method'] ?? null;

        // JSON-RPC response from client — ignore
        if ($method === null && array_key_exists('result', $message)) {
            return null;
        }

        if (!is_string($method) || $method === '') {
            return $this->error($id, -32600, 'Invalid Request');
        }

        // Notifications have no id
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
                            'version' => '1.0.0',
                        ],
                        'instructions' => 'Create StorageKeys blog drafts with create_blog (default status=0). Use list_blogs to verify. Only set status=1 when the user explicitly asks to publish.',
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

                    return $this->rpcResult($id, $this->callTool((string) $name, $arguments, $request));

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
                'description' => 'Create a blog post in the StorageKeys database. Defaults to draft (status=0) unless status=1 is passed.',
                'inputSchema' => [
                    'type' => 'object',
                    'properties' => [
                        'title' => [
                            'type' => 'string',
                            'minLength' => 3,
                            'maxLength' => 255,
                            'description' => 'Blog title',
                        ],
                        'description' => [
                            'type' => 'string',
                            'minLength' => 20,
                            'description' => 'Blog HTML or text body',
                        ],
                        'status' => [
                            'type' => 'integer',
                            'enum' => [0, 1],
                            'description' => '0 = draft (default), 1 = published/active',
                        ],
                        'image_url' => [
                            'type' => 'string',
                            'format' => 'uri',
                            'description' => 'Optional public image URL to download as blog cover',
                        ],
                        'slug' => [
                            'type' => 'string',
                            'description' => 'Optional custom slug; auto-generated from title if omitted',
                        ],
                    ],
                    'required' => ['title', 'description'],
                ],
            ],
        ];
    }

    /**
     * @param  array<string, mixed>  $arguments
     * @return array{content: list<array{type: string, text: string}>, isError?: bool}
     */
    private function callTool(string $name, array $arguments, Request $request): array
    {
        // Some clients nest fields under arguments; others put them on params root.
        $arguments = $this->normalizeToolArguments($arguments);

        $blogController = app(McpBlogController::class);

        if ($name === 'list_blogs') {
            $limit = isset($arguments['limit']) ? (int) $arguments['limit'] : 10;
            $sub = Request::create('/api/mcp/blogs', 'GET', ['limit' => $limit]);
            $sub->headers->set('Accept', 'application/json');
            $response = $blogController->index($sub);
            $data = $response->getData(true);

            return [
                'content' => [
                    [
                        'type' => 'text',
                        'text' => json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES),
                    ],
                ],
            ];
        }

        if ($name === 'create_blog') {
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
            if (array_key_exists('status', $arguments) && ($arguments['status'] === 0 || $arguments['status'] === 1 || $arguments['status'] === '0' || $arguments['status'] === '1')) {
                $payload['status'] = (int) $arguments['status'];
            }
            if (!empty($arguments['image_url']) && is_string($arguments['image_url'])) {
                $payload['image_url'] = $arguments['image_url'];
            }
            if (!empty($arguments['slug']) && is_string($arguments['slug'])) {
                $payload['slug'] = $arguments['slug'];
            }

            // Build a fresh JSON request. Do NOT copy the parent MCP Content-Type/body —
            // that made Laravel see an empty JSON body and fail "title/description required".
            $sub = Request::create(
                '/api/mcp/blogs',
                'POST',
                [],
                [],
                [],
                [
                    'CONTENT_TYPE' => 'application/json',
                    'HTTP_ACCEPT' => 'application/json',
                ],
                json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)
            );
            $response = $blogController->store($sub);
            $data = $response->getData(true);
            $status = $response->getStatusCode();

            if ($status >= 400) {
                return [
                    'content' => [
                        [
                            'type' => 'text',
                            'text' => json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES),
                        ],
                    ],
                    'isError' => true,
                ];
            }

            return [
                'content' => [
                    [
                        'type' => 'text',
                        'text' => json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES),
                    ],
                ],
            ];
        }

        return [
            'content' => [
                [
                    'type' => 'text',
                    'text' => 'Unknown tool: ' . $name,
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

        // If Claude sent the whole args object as a JSON string
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
