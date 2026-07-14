<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeadStorageUnit extends Model
{
    use HasFactory;

    protected $table = 'lead_storage_units';

    protected $fillable = [
        'lead_id',
        'storage_unit_id',
    ];

    public function lead()
    {
        return $this->belongsTo(Lead::class, 'lead_id', 'id');
    }

    public function storageunit()
    {
        return $this->belongsTo(StorageUnit::class, 'storage_unit_id', 'id');
    }
}
