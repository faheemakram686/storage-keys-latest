<?php

namespace App\Console\Commands\Backend;

use App\Models\Contract;
use App\Models\ContractStorageUnit;
use App\Models\StorageUnit;
use App\Models\StorageUnitAssignment;
use App\Services\StorageUnitStatusService;
use Illuminate\Console\Command;

class RecalculateStorageUnitStatus extends Command
{
    protected $signature = 'storage-units:recalculate-status {--unit= : Optional single storage unit id}';

    protected $description = 'Recalculate storage unit occupancy statuses from active contracts, payments, and move-in/out records';

    public function handle(StorageUnitStatusService $service): int
    {
        $unitId = $this->option('unit');

        if ($unitId) {
            $status = $service->recalculate((int) $unitId, 'artisan.recalculate');
            $this->info("Unit #{$unitId} → {$status}");
            return 0;
        }

        // Ensure hard assignments exist for active contract snapshots
        $links = ContractStorageUnit::query()
            ->active()
            ->whereHas('contract', function ($q) {
                $q->where('is_deleted', 0);
            })
            ->with('contract')
            ->get();

        $synced = 0;
        foreach ($links as $link) {
            $contract = $link->contract;
            if (!$contract) {
                continue;
            }

            $exists = StorageUnitAssignment::query()
                ->where('source_type', Contract::class)
                ->where('source_id', $contract->id)
                ->where('storage_unit_id', $link->storage_unit_id)
                ->where('hold_type', StorageUnitAssignment::HOLD_HARD)
                ->whereNull('released_at')
                ->exists();

            if (!$exists) {
                StorageUnitAssignment::updateOrCreate(
                    [
                        'source_type' => Contract::class,
                        'source_id' => $contract->id,
                        'storage_unit_id' => $link->storage_unit_id,
                        'hold_type' => StorageUnitAssignment::HOLD_HARD,
                    ],
                    [
                        'customer_id' => $contract->customer_id,
                        'released_at' => null,
                        'hard_hold_key' => $link->storage_unit_id,
                    ]
                );
                $synced++;
            }
        }

        $this->info("Synced {$synced} hard assignment(s) from contract snapshots.");

        $count = 0;
        StorageUnit::query()
            ->where('is_deleted', 0)
            ->orderBy('id')
            ->chunkById(100, function ($units) use ($service, &$count) {
                foreach ($units as $unit) {
                    $service->recalculate($unit->id, 'artisan.recalculate');
                    $count++;
                }
            });

        $this->info("Recalculated status for {$count} storage unit(s).");

        return 0;
    }
}
