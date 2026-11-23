<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * One-time browser upload page (create_image_upload_link). Only the SHA-256 of the token is stored.
 */
class ImageUploadLink extends Model
{
    protected $fillable = ['token_hash', 'purpose', 'alt', 'max_files', 'media_ids', 'expires_at', 'completed_at'];

    protected $casts = [
        'media_ids' => 'array',
        'expires_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    public static function findByToken(string $token): ?self
    {
        return self::query()->where('token_hash', hash('sha256', $token))->first();
    }

    public function status(): string
    {
        if ($this->completed_at) {
            return 'completed';
        }

        return $this->expires_at->isPast() ? 'expired' : 'waiting';
    }

    public function remaining(): int
    {
        return max(0, (int) $this->max_files - count($this->media_ids ?? []));
    }
}
