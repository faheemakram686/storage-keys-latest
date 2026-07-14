<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StorageUnitStatusLog extends Model
{
    use HasFactory;

    protected $table = 'storage_unit_status_logs';

    protected $fillable = [
        'storage_unit_id',
        'old_status',
        'new_status',
        'trigger',
        'reference_type',
        'reference_id',
        'user_id',
        'notes',
    ];

    public function storageUnit()
    {
        return $this->belongsTo(StorageUnit::class, 'storage_unit_id', 'id');
    }
}
