<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StorageUnitAssignment extends Model
{
    use HasFactory;

    public const HOLD_SOFT = 'soft';
    public const HOLD_HARD = 'hard';

    protected $table = 'storage_unit_assignments';

    protected $fillable = [
        'storage_unit_id',
        'customer_id',
        'hold_type',
        'source_type',
        'source_id',
        'released_at',
        'hard_hold_key',
    ];

    protected $casts = [
        'released_at' => 'datetime',
    ];

    public function storageUnit()
    {
        return $this->belongsTo(StorageUnit::class, 'storage_unit_id', 'id');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'id');
    }

    public function scopeActive($query)
    {
        return $query->whereNull('released_at');
    }

    public function scopeHard($query)
    {
        return $query->where('hold_type', self::HOLD_HARD);
    }

    public function scopeSoft($query)
    {
        return $query->where('hold_type', self::HOLD_SOFT);
    }
}
