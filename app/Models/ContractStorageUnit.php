<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContractStorageUnit extends Model
{
    use HasFactory;

    protected $table = 'contract_storage_units';

    protected $fillable = [
        'contract_id',
        'storage_unit_id',
        'unit_price',
        'released_at',
    ];

    protected $casts = [
        'released_at' => 'datetime',
    ];

    public function contract()
    {
        return $this->belongsTo(Contract::class, 'contract_id', 'id');
    }

    public function storageunit()
    {
        return $this->belongsTo(StorageUnit::class, 'storage_unit_id', 'id');
    }

    public function scopeActive($query)
    {
        return $query->whereNull('released_at');
    }
}
