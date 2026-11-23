<?php

namespace App\Services\Mcp;

use App\Models\ContentRevision;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

/**
 * Writes and reads content_revisions. Every row keeps full before/after snapshots
 * (raw attributes, no timestamps), so any revision can be restored.
 */
class Revisions
{
    private const IGNORED = ['created_at', 'updated_at'];

    public static function snapshot(Model $model, bool $original = false): array
    {
        $attrs = $original ? $model->getRawOriginal() : $model->getAttributes();

        return array_diff_key($attrs, array_flip(self::IGNORED));
    }

    public static function record(
        Model $model,
        string $event,
        ?array $before,
        ?array $after,
        ?string $summary = null
    ): ?ContentRevision {
        $type = ContentTypes::typeOf($model);
        if ($type === null || !self::tableReady()) {
            return null;
        }

        $changed = self::changedKeys($before, $after);
        if ($event === 'updated' && $changed === []) {
            return null;
        }

        return ContentRevision::create([
            'entity_type' => $type,
            'entity_id' => (int) $model->getKey(),
            'event' => $event,
            'source' => McpContext::source(),
            'tool' => McpContext::$tool,
            'summary' => $summary !== null ? Str::limit($summary, 250) : self::summary($event, $changed),
            'changed' => $changed,
            'before' => $before,
            'after' => $after,
            'created_at' => now(),
        ]);
    }

    /**
     * Code can be deployed before `php artisan migrate`; never break a save over a missing log table.
     */
    public static function tableReady(): bool
    {
        static $ready = null;

        return $ready ??= \Illuminate\Support\Facades\Schema::hasTable('content_revisions');
    }

    public static function changedKeys(?array $before, ?array $after): array
    {
        $before ??= [];
        $after ??= [];
        $keys = [];
        foreach (array_unique(array_merge(array_keys($before), array_keys($after))) as $key) {
            if (in_array($key, self::IGNORED, true)) {
                continue;
            }
            if ((string) ($before[$key] ?? '') !== (string) ($after[$key] ?? '')) {
                $keys[] = $key;
            }
        }

        return $keys;
    }

    private static function summary(string $event, array $changed): string
    {
        return $event === 'updated' ? 'Changed: ' . implode(', ', $changed) : ucfirst($event);
    }

    /**
     * Compact view for activity_log / list_*_revisions: only the changed fields, long text trimmed.
     */
    public static function present(ContentRevision $rev, bool $full = false, int $limit = 400): array
    {
        $diff = [];
        foreach ((array) $rev->changed as $key) {
            $diff[$key] = [
                'before' => self::trimValue($rev->before[$key] ?? null, $full, $limit),
                'after' => self::trimValue($rev->after[$key] ?? null, $full, $limit),
            ];
        }

        return [
            'revision_id' => $rev->id,
            'entity_type' => $rev->entity_type,
            'entity_id' => $rev->entity_id,
            'event' => $rev->event,
            'source' => $rev->source,
            'tool' => $rev->tool,
            'summary' => $rev->summary,
            'created_at' => optional($rev->created_at)->toDateTimeString(),
            'changes' => $diff,
        ];
    }

    private static function trimValue($value, bool $full, int $limit)
    {
        if (!is_string($value) || $full) {
            return $value;
        }

        return mb_strlen($value) > $limit ? mb_substr($value, 0, $limit) . '… (' . mb_strlen($value) . ' chars)' : $value;
    }
}
