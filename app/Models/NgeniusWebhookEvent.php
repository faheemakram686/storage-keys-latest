<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NgeniusWebhookEvent extends Model
{
    public const STATUS_RECEIVED = 'received';
    public const STATUS_PROCESSED = 'processed';
    public const STATUS_FAILED = 'failed';

    protected $fillable = [
        'event_id',
        'event_name',
        'order_reference',
        'status',
        'processed_at',
    ];

    protected $casts = [
        'processed_at' => 'datetime',
    ];

    public function markProcessed(): void
    {
        $this->status = self::STATUS_PROCESSED;
        $this->processed_at = now();
        $this->save();
    }

    public function markFailed(): void
    {
        $this->status = self::STATUS_FAILED;
        $this->processed_at = null;
        $this->save();
    }
}
