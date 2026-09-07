<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UrlRedirect extends Model
{
    protected $fillable = ['from_path', 'to_url', 'status_code', 'is_active'];

    protected $casts = [
        'is_active' => 'boolean',
        'status_code' => 'integer',
    ];

    public static function normalizePath(string $path): string
    {
        $path = trim(parse_url($path, PHP_URL_PATH) ?: $path);
        $path = '/' . ltrim($path, '/');
        if ($path !== '/') {
            $path = rtrim($path, '/');
        }

        return $path === '' ? '/' : $path;
    }
}
