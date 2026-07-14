<?php

namespace App\Services;

use App\Enums\StorageUnitStatus;
use App\Models\Contract;
use App\Models\ContractStorageUnit;
use App\Models\Estimate;
use App\Models\Invoice;
use App\Models\Lead;
use App\Models\MoveIn;
use App\Models\MoveOut;
use App\Models\StorageUnit;
use App\Models\StorageUnitAssignment;
use App\Models\StorageUnitStatusLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class StorageUnitStatusService
{
    /**
     * Recalculate and persist occupancy status for a unit.
     *
     * Priority:
     * 1. is_maintenance → under maintenance
     * 2. Active move-in without move-out → occupied
     * 3. Active hard contract + unpaid/partial invoice → booked but not paid
     * 4. Active hard contract + paid (or no unpaid balance) awaiting move-in → booked
     * 5. Else → vacant
     */
    public function recalculate(int $storageUnitId, string $trigger = 'recalculate', ?Model $reference = null): string
    {
        return DB::transaction(function () use ($storageUnitId, $trigger, $reference) {
            $unit = StorageUnit::lockForUpdate()->find($storageUnitId);
            if (!$unit) {
                return StorageUnitStatus::VACANT;
            }

            $newStatus = StorageUnitStatus::VACANT;
            $customerId = null;
            $contractId = null;

            if ($unit->is_maintenance) {
                $newStatus = StorageUnitStatus::UNDER_MAINTENANCE;
            } else {
                $activeContractLink = ContractStorageUnit::query()
                    ->active()
                    ->where('storage_unit_id', $storageUnitId)
                    ->whereHas('contract', function ($q) {
                        $q->where('is_deleted', 0);
                    })
                    ->with('contract')
                    ->orderByDesc('id')
                    ->first();

                if ($activeContractLink && $activeContractLink->contract) {
                    $contract = $activeContractLink->contract;
                    $contractId = $contract->id;
                    $customerId = $contract->customer_id;

                    $hasActiveMoveIn = MoveIn::query()
                        ->where('contract_id', $contract->id)
                        ->where('is_deleted', 0)
                        ->exists();

                    $hasActiveMoveOut = MoveOut::query()
                        ->where('contract_id', $contract->id)
                        ->where('is_deleted', 0)
                        ->exists();

                    if ($hasActiveMoveIn && !$hasActiveMoveOut) {
                        $newStatus = StorageUnitStatus::OCCUPIED;
                    } elseif ($hasActiveMoveOut) {
                        // Full move-out releases the lease occupancy; contract soft-release handled separately.
                        $newStatus = StorageUnitStatus::VACANT;
                        $customerId = null;
                        $contractId = null;
                    } else {
                        $newStatus = $this->statusFromContractPayment($contract);
                    }
                }
            }

            return $this->transition($unit, $newStatus, $trigger, $reference, $customerId, $contractId);
        });
    }

    /**
     * Recalculate many units (deduped).
     *
     * @param array<int, int|string> $storageUnitIds
     */
    public function recalculateMany(array $storageUnitIds, string $trigger = 'recalculate', ?Model $reference = null): void
    {
        $ids = array_values(array_unique(array_filter(array_map('intval', $storageUnitIds))));
        foreach ($ids as $id) {
            $this->recalculate($id, $trigger, $reference);
        }
    }

    public function transition(
        StorageUnit $unit,
        string $to,
        string $trigger,
        ?Model $reference = null,
        ?int $customerId = null,
        ?int $contractId = null,
        ?string $notes = null
    ): string {
        $to = StorageUnitStatus::normalize($to);
        $from = StorageUnitStatus::normalize($unit->status);

        $unit->status = $to;
        $unit->status_changed_at = now();
        $unit->occupied_by_customer_id = $to === StorageUnitStatus::VACANT ? null : $customerId;
        $unit->active_contract_id = in_array($to, [
            StorageUnitStatus::BOOKED,
            StorageUnitStatus::BOOKED_NOT_PAID,
            StorageUnitStatus::OCCUPIED,
        ], true) ? $contractId : null;

        if ($to === StorageUnitStatus::UNDER_MAINTENANCE) {
            $unit->is_maintenance = true;
        }

        $unit->save();

        if ($from !== $to) {
            StorageUnitStatusLog::create([
                'storage_unit_id' => $unit->id,
                'old_status' => $from,
                'new_status' => $to,
                'trigger' => $trigger,
                'reference_type' => $reference ? get_class($reference) : null,
                'reference_id' => $reference ? $reference->getKey() : null,
                'user_id' => Auth::id(),
                'notes' => $notes,
            ]);
        }

        return $to;
    }

    /**
     * Soft assign for lead/estimate — does not change occupancy status.
     *
     * @param array<int, int> $storageUnitIds
     */
    public function syncSoftAssignments(Model $source, array $storageUnitIds, ?int $customerId = null): void
    {
        $storageUnitIds = array_values(array_unique(array_filter(array_map('intval', $storageUnitIds))));
        $sourceType = get_class($source);
        $sourceId = $source->getKey();

        StorageUnitAssignment::query()
            ->where('source_type', $sourceType)
            ->where('source_id', $sourceId)
            ->where('hold_type', StorageUnitAssignment::HOLD_SOFT)
            ->whereNull('released_at')
            ->whereNotIn('storage_unit_id', $storageUnitIds ?: [0])
            ->update(['released_at' => now(), 'hard_hold_key' => null]);

        foreach ($storageUnitIds as $unitId) {
            $existing = StorageUnitAssignment::query()
                ->where('source_type', $sourceType)
                ->where('source_id', $sourceId)
                ->where('storage_unit_id', $unitId)
                ->where('hold_type', StorageUnitAssignment::HOLD_SOFT)
                ->whereNull('released_at')
                ->first();

            if ($existing) {
                if ($customerId && !$existing->customer_id) {
                    $existing->customer_id = $customerId;
                    $existing->save();
                }
                continue;
            }

            StorageUnitAssignment::create([
                'storage_unit_id' => $unitId,
                'customer_id' => $customerId,
                'hold_type' => StorageUnitAssignment::HOLD_SOFT,
                'source_type' => $sourceType,
                'source_id' => $sourceId,
                'released_at' => null,
                'hard_hold_key' => null,
            ]);
        }
    }

    /**
     * Create hard holds + snapshot rows for a contract, then recalculate status.
     *
     * @param array<int, array{storage_unit_id:int, unit_price?:mixed}> $unitRows
     */
    public function hardHoldContract(Contract $contract, array $unitRows): void
    {
        $unitIds = [];

        foreach ($unitRows as $row) {
            $unitId = (int) ($row['storage_unit_id'] ?? 0);
            if ($unitId <= 0) {
                continue;
            }
            $unitIds[] = $unitId;

            ContractStorageUnit::updateOrCreate(
                [
                    'contract_id' => $contract->id,
                    'storage_unit_id' => $unitId,
                ],
                [
                    'unit_price' => $row['unit_price'] ?? null,
                    'released_at' => null,
                ]
            );

            $assignment = StorageUnitAssignment::query()
                ->where('source_type', Contract::class)
                ->where('source_id', $contract->id)
                ->where('storage_unit_id', $unitId)
                ->where('hold_type', StorageUnitAssignment::HOLD_HARD)
                ->first();

            if ($assignment) {
                $assignment->released_at = null;
                $assignment->customer_id = $contract->customer_id;
                $assignment->hard_hold_key = $unitId;
                $assignment->save();
            } else {
                StorageUnitAssignment::create([
                    'storage_unit_id' => $unitId,
                    'customer_id' => $contract->customer_id,
                    'hold_type' => StorageUnitAssignment::HOLD_HARD,
                    'source_type' => Contract::class,
                    'source_id' => $contract->id,
                    'released_at' => null,
                    'hard_hold_key' => $unitId,
                ]);
            }
        }

        $this->recalculateMany($unitIds, 'contract.created', $contract);
    }

    public function releaseByContract(Contract $contract, string $trigger = 'contract.released'): void
    {
        $unitIds = ContractStorageUnit::query()
            ->where('contract_id', $contract->id)
            ->pluck('storage_unit_id')
            ->map(function ($id) {
                return (int) $id;
            })
            ->all();

        ContractStorageUnit::query()
            ->where('contract_id', $contract->id)
            ->whereNull('released_at')
            ->update(['released_at' => now()]);

        StorageUnitAssignment::query()
            ->where('source_type', Contract::class)
            ->where('source_id', $contract->id)
            ->whereNull('released_at')
            ->update([
                'released_at' => now(),
                'hard_hold_key' => null,
            ]);

        $this->recalculateMany($unitIds, $trigger, $contract);
    }

    public function releaseByLead(Lead $lead, string $trigger = 'lead.released'): void
    {
        $this->releaseSoftSource(Lead::class, $lead->id, $trigger, $lead);
    }

    public function releaseByEstimate(Estimate $estimate, string $trigger = 'estimate.released'): void
    {
        $this->releaseSoftSource(Estimate::class, $estimate->id, $trigger, $estimate);
    }

    /**
     * Assert units can be soft-held (lead/estimate). Blocks maintenance + occupied + hard-held
     * (unless the hard hold belongs to a contract created from $excludeEstimateId).
     *
     * @param array<int, int|string> $storageUnitIds
     * @throws ValidationException
     */
    public function assertAvailableForEstimate(array $storageUnitIds, ?int $excludeEstimateId = null): void
    {
        $ids = array_values(array_unique(array_filter(array_map('intval', $storageUnitIds))));
        if (empty($ids)) {
            return;
        }

        $units = StorageUnit::whereIn('id', $ids)->get()->keyBy('id');
        $errors = [];

        foreach ($ids as $id) {
            $unit = $units->get($id);
            if (!$unit || (int) $unit->is_deleted === 1) {
                $errors[] = "Storage unit #{$id} was not found.";
                continue;
            }
            if ($unit->is_maintenance || StorageUnitStatus::normalize($unit->status) === StorageUnitStatus::UNDER_MAINTENANCE) {
                $errors[] = "Storage unit {$unit->storage_unit_name} is under maintenance.";
                continue;
            }
            if (StorageUnitStatus::normalize($unit->status) === StorageUnitStatus::OCCUPIED) {
                $errors[] = "Storage unit {$unit->storage_unit_name} is occupied.";
                continue;
            }
            if ($this->hasActiveHardHold($id, null, $excludeEstimateId)) {
                $errors[] = "Storage unit {$unit->storage_unit_name} is already booked under an active contract.";
            }
        }

        if (!empty($errors)) {
            throw ValidationException::withMessages(['storage_units' => $errors]);
        }
    }

    /**
     * Assert units can receive a new hard contract hold.
     *
     * @param array<int, int|string> $storageUnitIds
     * @throws ValidationException
     */
    public function assertAvailableForContract(array $storageUnitIds, ?int $excludeContractId = null): void
    {
        $ids = array_values(array_unique(array_filter(array_map('intval', $storageUnitIds))));
        if (empty($ids)) {
            throw ValidationException::withMessages([
                'storage_units' => ['At least one storage unit is required for a contract.'],
            ]);
        }

        $units = StorageUnit::whereIn('id', $ids)->get()->keyBy('id');
        $errors = [];

        foreach ($ids as $id) {
            $unit = $units->get($id);
            if (!$unit || (int) $unit->is_deleted === 1) {
                $errors[] = "Storage unit #{$id} was not found.";
                continue;
            }
            if ($unit->is_maintenance || StorageUnitStatus::normalize($unit->status) === StorageUnitStatus::UNDER_MAINTENANCE) {
                $errors[] = "Storage unit {$unit->storage_unit_name} is under maintenance and cannot be contracted.";
                continue;
            }

            $status = StorageUnitStatus::normalize($unit->status);
            if ($status === StorageUnitStatus::OCCUPIED) {
                $errors[] = "Storage unit {$unit->storage_unit_name} is occupied.";
                continue;
            }

            if ($this->hasActiveHardHold($id, $excludeContractId)) {
                $errors[] = "Storage unit {$unit->storage_unit_name} is already booked under another contract.";
                continue;
            }

            // Vacant-only for new contracts (soft estimates do not change status)
            if ($status !== StorageUnitStatus::VACANT) {
                $errors[] = "Storage unit {$unit->storage_unit_name} is not vacant (current status: {$status}).";
            }
        }

        if (!empty($errors)) {
            throw ValidationException::withMessages(['storage_units' => $errors]);
        }
    }

    /**
     * Alias for lead soft-hold checks (same rules as estimate).
     *
     * @param array<int, int|string> $storageUnitIds
     */
    public function assertAvailableForLead(array $storageUnitIds): void
    {
        $this->assertAvailableForEstimate($storageUnitIds);
    }

    public function setMaintenance(StorageUnit $unit, bool $enabled, ?string $notes = null): string
    {
        if ($enabled && StorageUnitStatus::normalize($unit->status) === StorageUnitStatus::OCCUPIED) {
            throw ValidationException::withMessages([
                'status' => ['Cannot put an occupied unit under maintenance. Complete move-out first.'],
            ]);
        }

        $unit->is_maintenance = $enabled;
        if (!$enabled && StorageUnitStatus::normalize($unit->status) === StorageUnitStatus::UNDER_MAINTENANCE) {
            $unit->status = StorageUnitStatus::VACANT;
        }
        $unit->save();

        return $this->recalculate(
            $unit->id,
            $enabled ? 'admin.maintenance_on' : 'admin.maintenance_off',
            $unit
        );
    }

    /**
     * Recalculate units linked to a contract (e.g. after payment sync).
     */
    public function recalculateForContract(Contract $contract, string $trigger = 'payment.synced'): void
    {
        $unitIds = ContractStorageUnit::query()
            ->where('contract_id', $contract->id)
            ->whereNull('released_at')
            ->pluck('storage_unit_id')
            ->all();

        if (empty($unitIds) && $contract->estimate_id) {
            $unitIds = DB::table('estimate_storage_units')
                ->where('estimate_id', $contract->estimate_id)
                ->pluck('storage_unit_id')
                ->all();
        }

        $this->recalculateMany($unitIds, $trigger, $contract);
    }

    public function hasActiveHardHold(int $storageUnitId, ?int $excludeContractId = null, ?int $excludeEstimateId = null): bool
    {
        $excludedContractIds = [];
        if ($excludeContractId) {
            $excludedContractIds[] = $excludeContractId;
        }
        if ($excludeEstimateId) {
            $fromEstimate = Contract::query()
                ->where('estimate_id', $excludeEstimateId)
                ->where('is_deleted', 0)
                ->pluck('id')
                ->all();
            $excludedContractIds = array_merge($excludedContractIds, $fromEstimate);
        }
        $excludedContractIds = array_values(array_unique(array_filter($excludedContractIds)));

        $assignmentExists = StorageUnitAssignment::query()
            ->active()
            ->hard()
            ->where('storage_unit_id', $storageUnitId)
            ->where('source_type', Contract::class)
            ->when(!empty($excludedContractIds), function ($q) use ($excludedContractIds) {
                $q->whereNotIn('source_id', $excludedContractIds);
            })
            ->exists();

        if ($assignmentExists) {
            return true;
        }

        return ContractStorageUnit::query()
            ->active()
            ->where('storage_unit_id', $storageUnitId)
            ->whereHas('contract', function ($c) use ($excludedContractIds) {
                $c->where('is_deleted', 0);
                if (!empty($excludedContractIds)) {
                    $c->whereNotIn('id', $excludedContractIds);
                }
            })
            ->exists();
    }

    protected function statusFromContractPayment(Contract $contract): string
    {
        $invoice = Invoice::query()
            ->where('contract_id', $contract->id)
            ->where('is_deleted', 0)
            ->orderByDesc('id')
            ->first();

        if (!$invoice) {
            return StorageUnitStatus::BOOKED_NOT_PAID;
        }

        $paymentStatus = $invoice->resolvePaymentStatusValue();
        if ($paymentStatus === Invoice::PAYMENT_PAID) {
            return StorageUnitStatus::BOOKED;
        }

        return StorageUnitStatus::BOOKED_NOT_PAID;
    }

    protected function releaseSoftSource(string $sourceType, int $sourceId, string $trigger, Model $reference): void
    {
        $unitIds = StorageUnitAssignment::query()
            ->where('source_type', $sourceType)
            ->where('source_id', $sourceId)
            ->whereNull('released_at')
            ->pluck('storage_unit_id')
            ->all();

        StorageUnitAssignment::query()
            ->where('source_type', $sourceType)
            ->where('source_id', $sourceId)
            ->whereNull('released_at')
            ->update([
                'released_at' => now(),
                'hard_hold_key' => null,
            ]);

        // Soft holds do not change status; recalculate keeps invariants if a hard hold also exists.
        $this->recalculateMany($unitIds, $trigger, $reference);
    }
}
