<?php

namespace App\Models;

use App\Enums\StorageUnitStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StorageUnit extends Model
{
    use HasFactory;

    protected $fillable = [
        'storage_unit_name',
        'wh_id',
        'stype_id',
        'slevel_id',
        'ssize_id',
        'width',
        'length',
        'height',
        'price',
        'location',
        'status',
        'is_maintenance',
        'status_changed_at',
        'occupied_by_customer_id',
        'active_contract_id',
        'is_deleted',
    ];

    protected $casts = [
        'is_maintenance' => 'boolean',
        'status_changed_at' => 'datetime',
    ];

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class, 'wh_id', 'id')->select(['id', 'name', 'loc_id']);
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

    public function occupiedByCustomer()
    {
        return $this->belongsTo(Customer::class, 'occupied_by_customer_id', 'id');
    }

    public function activeContract()
    {
        return $this->belongsTo(Contract::class, 'active_contract_id', 'id');
    }

    public function statusLogs()
    {
        return $this->hasMany(StorageUnitStatusLog::class, 'storage_unit_id', 'id');
    }

    public function assignments()
    {
        return $this->hasMany(StorageUnitAssignment::class, 'storage_unit_id', 'id');
    }

    public function contractStorageUnits()
    {
        return $this->hasMany(ContractStorageUnit::class, 'storage_unit_id', 'id');
    }

    public function scopeNotDeleted($query)
    {
        return $query->where('is_deleted', 0);
    }

    public function scopeAvailableForBooking($query)
    {
        return $query->where('is_deleted', 0)
            ->where('is_maintenance', false)
            ->where('status', StorageUnitStatus::VACANT);
    }

    public function scopeAvailableForEstimate($query)
    {
        return $query->where('is_deleted', 0)
            ->where('is_maintenance', false)
            ->whereNotIn('status', [
                StorageUnitStatus::OCCUPIED,
                StorageUnitStatus::UNDER_MAINTENANCE,
            ]);
    }

    public function scopeAvailableForContract($query)
    {
        return $query->where('is_deleted', 0)
            ->where('is_maintenance', false)
            ->where('status', StorageUnitStatus::VACANT);
    }

    public function scopeUnderMaintenance($query)
    {
        return $query->where(function ($q) {
            $q->where('is_maintenance', true)
                ->orWhere('status', StorageUnitStatus::UNDER_MAINTENANCE);
        });
    }

    public function scopeOccupied($query)
    {
        return $query->where('status', StorageUnitStatus::OCCUPIED);
    }

    public function isSelectableForBooking(): bool
    {
        return !$this->is_maintenance
            && StorageUnitStatus::normalize($this->status) === StorageUnitStatus::VACANT
            && (int) $this->is_deleted === 0;
    }
}
