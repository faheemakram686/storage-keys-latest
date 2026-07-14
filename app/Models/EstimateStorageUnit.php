<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EstimateStorageUnit extends Model
{
    use HasFactory;

    protected $table = 'estimate_storage_units';

    protected $fillable = [
        'estimate_id',
        'storage_unit_id',
        'unit_price',
    ];

    public function estimate()
    {
        return $this->belongsTo(Estimate::class, 'estimate_id', 'id');
    }

    public function storageunit()
    {
        return $this->belongsTo(StorageUnit::class, 'storage_unit_id', 'id');
    }
}
