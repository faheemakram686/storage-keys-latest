<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StorageUnit extends Model
{
    use HasFactory;
    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class, 'wh_id', 'id')->select(['id', 'name','loc_id']);
    }
    public function storagetype()
    {
        return $this->belongsTo(StorageType::class, 'stype_id', 'id')->select(['id', 'name']);
    }
    public function storagelevel()
    {
        return $this->belongsTo(StorageUnitLevel::class, 'slevel_id', 'id')->select(['id', 'name']);
    }
    public function storagesize()
    {
        return $this->belongsTo(StorageUnitSize::class, 'ssize_id', 'id')->select(['id', 'unit_type_name']);
    }
}
