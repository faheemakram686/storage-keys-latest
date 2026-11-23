<?php

namespace App\Services\Mcp\Tools;

use App\Models\ContentRevision;
use App\Services\Mcp\ContentTypes;
use App\Services\Mcp\McpException;
use App\Services\Mcp\Revisions;
use App\Services\Mcp\SiteCache;
use Illuminate\Support\Facades\DB;

/**
 * Phase 1: activity log, trash, restore, purge, cache.
 */
class SafetyTools extends Toolset
{
    public function definitions(): array
    {
        $types = ContentTypes::names();

        return [
            $this->tool('activity_log', 'Recent content changes (MCP and admin panel) with before/after values of the changed fields, source, tool and time. Long text is trimmed unless full=true.', [
                'limit' => ['type' => 'integer', 'minimum' => 1, 'maximum' => 200],
                'entity_type' => ['type' => 'string', 'enum' => $types],
                'entity_id' => ['type' => 'integer'],
                'full' => ['type' => 'boolean', 'description' => 'Return full before/after text'],
            ]),
            $this->tool('list_trash', 'Soft-deleted items that can be restored with restore_item or removed for good with purge_item.', [
                'type' => ['type' => 'string', 'enum' => $types],
                'limit' => ['type' => 'integer', 'minimum' => 1, 'maximum' => 200],
            ]),
            $this->tool('restore_item', 'Restore a soft-deleted item from the trash. It comes back with the status it had (a published page is live again).', [
                'type' => ['type' => 'string', 'enum' => $types],
                'id' => ['type' => 'integer'],
            ], ['type', 'id']),
            $this->tool('purge_item', 'Permanently delete an item that is already in the trash. Cannot be undone (the revision history keeps its last content). Requires confirm=true.', [
                'type' => ['type' => 'string', 'enum' => $types],
                'id' => ['type' => 'integer'],
                'confirm' => ['type' => 'boolean'],
            ], ['type', 'id', 'confirm']),
            $this->tool('clear_cache', 'Clear the site cache (compiled views and application cache). The site has no per-URL page cache, so url only checks the page renders after clearing.', [
                'url' => ['type' => 'string', 'description' => 'Optional path or URL on storagekeys.com to check after clearing'],
            ]),
        ];
    }

    public function call(string $name, array $a): array
    {
        switch ($name) {
            case 'activity_log':
                return $this->activityLog($a);
            case 'list_trash':
                return $this->listTrash($a);
            case 'restore_item':
                return $this->restore($a);
            case 'purge_item':
                return $this->purge($a);
            case 'clear_cache':
                return SiteCache::clear($this->str($a, 'url'));
        }

        throw new McpException('Unknown tool: ' . $name, 404);
    }

    private function activityLog(array $a): array
    {
        $q = ContentRevision::query()->orderByDesc('id');
        if ($type = $this->str($a, 'entity_type')) {
            ContentTypes::get($type);
            $q->where('entity_type', $type);
        }
        if ($id = $this->int($a, 'entity_id')) {
            $q->where('entity_id', $id);
        }
        $full = $this->bool($a, 'full');
        $rows = $q->limit($this->int($a, 'limit', 30, false, 1, 200))->get();

        return [
            'count' => $rows->count(),
            'entries' => $rows->map(fn (ContentRevision $r) => Revisions::present($r, $full))->values(),
        ];
    }

    private function listTrash(array $a): array
    {
        $only = $this->str($a, 'type');
        $types = $only ? [$only => ContentTypes::get($only)] : ContentTypes::all();
        $limit = $this->int($a, 'limit', 50, false, 1, 200);

        $items = [];
        foreach ($types as $type => $def) {
            $rows = $def['model']::query()->where($def['trash'], 1)->orderByDesc('updated_at')->limit($limit)->get();
            foreach ($rows as $row) {
                $items[] = [
                    'type' => $type,
                    'id' => $row->getKey(),
                    'label' => (string) $row->getRawOriginal($def['label']),
                    'slug' => $row->getRawOriginal('slug'),
                    'trashed_at' => (string) $row->getRawOriginal('updated_at'),
                ];
            }
        }

        return ['count' => count($items), 'items' => $items];
    }

    private function restore(array $a): array
    {
        $type = $this->str($a, 'type', true);
        $model = ContentTypes::find($type, $this->int($a, 'id', null, true));
        if (!ContentTypes::isTrashed($model)) {
            throw new McpException(ucfirst($type) . ' ' . $model->getKey() . ' is not in the trash.', 422);
        }

        DB::transaction(function () use ($type, $model) {
            $model->setAttribute(ContentTypes::get($type)['trash'], 0);
            $model->save();
            ContentTypes::runRestoreHooks($type, $model);
        });
        SiteCache::touch();

        return [
            'message' => ucfirst($type) . ' restored.',
            'type' => $type,
            'id' => $model->getKey(),
            'label' => ContentTypes::label($model),
            'updated_at' => (string) $model->getRawOriginal('updated_at'),
        ];
    }

    private function purge(array $a): array
    {
        $type = $this->str($a, 'type', true);
        $model = ContentTypes::find($type, $this->int($a, 'id', null, true));
        $this->requireConfirm($a, 'Permanent delete');
        if (!ContentTypes::isTrashed($model)) {
            throw new McpException('Only items in the trash can be purged. Delete it first (it goes to the trash), then purge.', 422);
        }

        $label = ContentTypes::label($model);
        DB::transaction(function () use ($type, $model) {
            ContentTypes::runPurgeHooks($type, $model);
            $model->delete();
        });
        SiteCache::touch();

        return [
            'message' => ucfirst($type) . ' "' . $label . '" permanently deleted. Its last content is kept in activity_log.',
            'type' => $type,
            'id' => (int) $model->getKey(),
        ];
    }
}
