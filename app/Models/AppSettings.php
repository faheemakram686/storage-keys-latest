<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AppSettings extends Model
{
    use HasFactory;

    public const MODE_MULTI_LEVEL = 'multi_level';
    public const MODE_DIRECT = 'direct';

    public static function approvalMode(): string
    {
        $value = static::where('key', 'approval_mode')->value('value');

        return $value === self::MODE_DIRECT ? self::MODE_DIRECT : self::MODE_MULTI_LEVEL;
    }

    public static function isDirectApproval(): bool
    {
        return static::approvalMode() === self::MODE_DIRECT;
    }
}
