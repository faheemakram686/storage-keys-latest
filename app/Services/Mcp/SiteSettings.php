<?php

namespace App\Services\Mcp;

use App\Models\ContentRevision;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Contact details and figures used by {{placeholders}} in DB pages and blog posts.
 * Stored in app_settings as "site.<key>"; the defaults are the values the coded pages show today.
 */
class SiteSettings
{
    private const PREFIX = 'site.';

    public const DEFAULTS = [
        'phone' => '+971 56 501 8785',
        'whatsapp' => '971565018785',
        'email' => 'sales@storagekeys.com',
        'toll_free' => '800 5397',
        'hours' => 'Open 24/7',
    ];

    public const DESCRIPTIONS = [
        'phone' => 'Main phone number as shown to visitors',
        'whatsapp' => 'WhatsApp number, digits only with country code (used for {{wa_link}})',
        'email' => 'Sales email address',
        'toll_free' => 'Toll-free number',
        'hours' => 'Opening hours text',
    ];

    /** Fixed keys plus free stat_* and custom_* keys (for {{setting:key}}). */
    public static function validKey(string $key): bool
    {
        return isset(self::DEFAULTS[$key]) || (bool) preg_match('/^(stat|custom)_[a-z0-9_]{1,40}$/', $key);
    }

    private static ?array $cache = null;

    /** Forget values read in this process (after writes; between tests). */
    public static function flush(): void
    {
        self::$cache = null;
    }

    public static function all(): array
    {
        if (self::$cache !== null) {
            return self::$cache;
        }
        $values = self::DEFAULTS;
        if (Schema::hasTable('app_settings')) {
            foreach (DB::table('app_settings')->where('key', 'like', self::PREFIX . '%')->pluck('value', 'key') as $k => $v) {
                $values[substr($k, strlen(self::PREFIX))] = (string) $v;
            }
        }

        return self::$cache = $values;
    }

    public static function get(string $key): ?string
    {
        return self::all()[$key] ?? null;
    }

    public static function set(string $key, ?string $value): array
    {
        if (!self::validKey($key)) {
            throw new McpException('Unknown setting "' . $key . '". Use ' . implode(', ', array_keys(self::DEFAULTS)) . ', or stat_<name> / custom_<name>.', 422);
        }
        $value = $value === null ? null : trim(strip_tags($value));
        self::validate($key, $value);

        $before = self::get($key);
        if ($value === null || $value === '') {
            DB::table('app_settings')->where('key', self::PREFIX . $key)->delete();
        } else {
            DB::table('app_settings')->updateOrInsert(
                ['key' => self::PREFIX . $key],
                ['value' => $value, 'description' => self::DESCRIPTIONS[$key] ?? 'Site setting', 'updated_at' => now(), 'created_at' => now()]
            );
        }
        self::$cache = null;
        $after = self::get($key);

        if (Revisions::tableReady() && $before !== $after) {
            ContentRevision::create([
                'entity_type' => 'setting',
                'entity_id' => 0,
                'event' => 'updated',
                'source' => McpContext::source(),
                'tool' => McpContext::$tool,
                'summary' => 'Setting ' . $key . ' changed',
                'changed' => [$key],
                'before' => [$key => $before],
                'after' => [$key => $after],
                'created_at' => now(),
            ]);
        }
        SiteCache::touch();

        return ['key' => $key, 'before' => $before, 'value' => $after, 'reset_to_default' => ($value === null || $value === '') && isset(self::DEFAULTS[$key])];
    }

    private static function validate(string $key, ?string $value): void
    {
        if ($value === null || $value === '') {
            return;
        }
        if (mb_strlen($value) > 500) {
            throw new McpException('Value is too long.', 422);
        }
        if ($key === 'email' && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
            throw new McpException('email is not a valid email address.', 422);
        }
        if ($key === 'whatsapp' && !preg_match('/^[1-9][0-9]{7,14}$/', $value)) {
            throw new McpException('whatsapp must be digits only with the country code, e.g. 971565018785.', 422);
        }
        if (in_array($key, ['phone', 'toll_free'], true) && !preg_match('/^\+?[0-9 ()-]{5,25}$/', $value)) {
            throw new McpException($key . ' must be a phone number.', 422);
        }
    }
}
