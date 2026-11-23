<?php

namespace App\Services\Mcp;

use App\Models\Blog;
use App\Models\Page;
use App\Models\PageSection;
use Illuminate\Database\Eloquent\Model;

/**
 * Content the MCP tools track: revisions, trash, restore and purge.
 * Each type: model class, a label column for listings, and the column that marks it trashed.
 * Later phases add their models here (sections, images, FAQs, ...).
 */
class ContentTypes
{
    /** @var array<string, array{model: class-string<Model>, label: string, trash: string}> */
    private static array $types = [
        'page' => ['model' => Page::class, 'label' => 'title', 'trash' => 'is_deleted'],
        'blog' => ['model' => Blog::class, 'label' => 'title', 'trash' => 'is_deleted'],
        'section' => ['model' => PageSection::class, 'label' => 'name', 'trash' => 'is_deleted'],
    ];

    /** @var array<string, list<callable(Model): void>> */
    private static array $restoreHooks = [];

    /** Follow-up after a record of $type comes back from the trash (e.g. rebuild the page). */
    public static function onRestore(string $type, callable $hook): void
    {
        self::$restoreHooks[$type][] = $hook;
    }

    public static function runRestoreHooks(string $type, Model $model): void
    {
        foreach (self::$restoreHooks[$type] ?? [] as $hook) {
            $hook($model);
        }
    }

    /** @var array<string, list<callable(Model): void>> */
    private static array $purgeHooks = [];

    /**
     * Cleanup to run before a record of $type is permanently deleted (pivot rows, child records).
     */
    public static function onPurge(string $type, callable $hook): void
    {
        self::$purgeHooks[$type][] = $hook;
    }

    public static function runPurgeHooks(string $type, Model $model): void
    {
        foreach (self::$purgeHooks[$type] ?? [] as $hook) {
            $hook($model);
        }
    }

    public static function register(string $type, string $model, string $label, string $trash = 'is_deleted'): void
    {
        self::$types[$type] = ['model' => $model, 'label' => $label, 'trash' => $trash];
    }

    /** @return array<string, array{model: class-string<Model>, label: string, trash: string}> */
    public static function all(): array
    {
        return self::$types;
    }

    public static function names(): array
    {
        return array_keys(self::$types);
    }

    /** @return array{model: class-string<Model>, label: string, trash: string} */
    public static function get(string $type): array
    {
        if (!isset(self::$types[$type])) {
            throw new McpException('Unknown type "' . $type . '". Use one of: ' . implode(', ', self::names()) . '.', 422);
        }

        return self::$types[$type];
    }

    public static function typeOf(Model $model): ?string
    {
        foreach (self::$types as $type => $def) {
            if ($model instanceof $def['model']) {
                return $type;
            }
        }

        return null;
    }

    public static function find(string $type, $id): Model
    {
        $def = self::get($type);
        $model = $def['model']::query()->find((int) $id);
        if (!$model) {
            throw McpException::notFound(ucfirst($type) . ' ' . $id);
        }

        return $model;
    }

    public static function isTrashed(Model $model): bool
    {
        $def = self::get(self::typeOf($model));

        return (int) $model->getRawOriginal($def['trash']) === 1;
    }

    public static function label(Model $model): string
    {
        $def = self::get(self::typeOf($model));

        return (string) $model->getRawOriginal($def['label']);
    }
}
