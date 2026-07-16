<?php

namespace App\Helpers;

use Hashids\Hashids;

class Hashid
{
    protected static ?Hashids $instance = null;

    protected static function instance(): Hashids
    {
        if (self::$instance === null) {
            self::$instance = new Hashids(config('app.key'), 10);
        }

        return self::$instance;
    }

    public static function encode(int $id): string
    {
        return self::instance()->encode($id);
    }

    public static function decode(string $value): ?int
    {
        $decoded = self::instance()->decode($value);

        if (empty($decoded)) {
            return null;
        }

        return (int) $decoded[0];
    }
}
