<?php

namespace App\Services\Mcp;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * Read-before-write. Read tools return updated_at; update tools pass it back as expected_updated_at
 * and are refused when the record changed since.
 *
 * updated_at has one-second resolution, so ContentRevisionObserver moves it forward by at least
 * one second on every write. That way two writes in the same second still get different stamps.
 */
class Versioning
{
    public static function stamp(Model $model): ?string
    {
        $raw = $model->getRawOriginal('updated_at');

        return $raw === null ? null : Carbon::parse($raw)->format('Y-m-d H:i:s');
    }

    /**
     * @param  mixed  $expected
     */
    public static function assertCurrent(Model $model, $expected, string $label = 'Record'): void
    {
        if ($expected === null || $expected === '') {
            throw new McpException(
                'expected_updated_at is required. Read the record first and pass back its updated_at.',
                422,
                ['current_updated_at' => self::stamp($model)]
            );
        }

        try {
            $given = Carbon::parse((string) $expected)->format('Y-m-d H:i:s');
        } catch (\Throwable $e) {
            throw new McpException('expected_updated_at is not a valid date-time.', 422);
        }

        $current = self::stamp($model);
        if ($given !== $current) {
            throw new McpException(
                $label . ' changed since you read it (updated_at is now ' . $current . '). Read it again and retry.',
                409,
                ['current_updated_at' => $current]
            );
        }
    }

    /**
     * Called while saving: never reuse the previous stamp.
     */
    public static function bump(Model $model): void
    {
        if (!$model->usesTimestamps() || !$model->exists) {
            return;
        }
        $old = $model->getRawOriginal('updated_at');
        if ($old === null) {
            return;
        }
        // Compare whole seconds: the column has no fractions, so "now" in the same second is not newer.
        $now = Carbon::now()->startOfSecond();
        $oldAt = Carbon::parse($old)->startOfSecond();
        if ($now->lessThanOrEqualTo($oldAt)) {
            $model->updated_at = $oldAt->copy()->addSecond();
        }
    }
}
