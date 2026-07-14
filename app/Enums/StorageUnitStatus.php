<?php

namespace App\Enums;

/**
 * Storage unit occupancy statuses (string-backed for PHP 7.3/8.0 compatibility).
 */
class StorageUnitStatus
{
    public const VACANT = 'vacant';
    public const BOOKED = 'booked';
    public const BOOKED_NOT_PAID = 'booked but not paid';
    public const OCCUPIED = 'occupied';
    public const UNDER_MAINTENANCE = 'under maintenance';

    /**
     * @return array<int, string>
     */
    public static function all(): array
    {
        return [
            self::VACANT,
            self::BOOKED,
            self::BOOKED_NOT_PAID,
            self::OCCUPIED,
            self::UNDER_MAINTENANCE,
        ];
    }

    public static function isValid(string $status): bool
    {
        return in_array($status, self::all(), true);
    }

    /**
     * @return array<int, string>
     */
    public static function blockingForContract(): array
    {
        return [
            self::BOOKED,
            self::BOOKED_NOT_PAID,
            self::OCCUPIED,
            self::UNDER_MAINTENANCE,
        ];
    }

    /**
     * Normalize legacy/free-text status values to a known constant.
     */
    public static function normalize(?string $status): string
    {
        if ($status === null || $status === '') {
            return self::VACANT;
        }

        $normalized = strtolower(trim($status));
        $aliases = [
            'booked but not paid' => self::BOOKED_NOT_PAID,
            'booked_but_not_paid' => self::BOOKED_NOT_PAID,
            'booked-but-not-paid' => self::BOOKED_NOT_PAID,
            'under maintenance' => self::UNDER_MAINTENANCE,
            'under_maintenance' => self::UNDER_MAINTENANCE,
            'maintenance' => self::UNDER_MAINTENANCE,
        ];

        if (isset($aliases[$normalized])) {
            return $aliases[$normalized];
        }

        return self::isValid($normalized) ? $normalized : self::VACANT;
    }
}
