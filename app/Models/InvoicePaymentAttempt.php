<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InvoicePaymentAttempt extends Model
{
    public const STATE_CREATED = 'created';
    public const STATE_PENDING = 'pending';
    public const STATE_PAID = 'paid';
    public const STATE_FAILED = 'failed';
    public const STATE_CANCELLED = 'cancelled';

    public const SOURCE_HOSTED = 'hosted';
    public const SOURCE_MOBILE = 'mobile';
    public const SOURCE_RECURRING = 'recurring';

    protected $fillable = [
        'invoice_id',
        'order_reference',
        'expected_amount',
        'currency',
        'state',
        'source',
        'save_card',
        'retry_count',
    ];

    protected $casts = [
        'expected_amount' => 'float',
        'save_card' => 'boolean',
        'retry_count' => 'integer',
    ];

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    public static function findByOrderReference(string $reference): ?self
    {
        return static::query()->where('order_reference', $reference)->first();
    }
}
