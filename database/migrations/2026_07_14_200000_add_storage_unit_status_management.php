<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('storage_units', function (Blueprint $table) {
            if (!Schema::hasColumn('storage_units', 'is_maintenance')) {
                $table->boolean('is_maintenance')->default(false)->after('status');
            }
            if (!Schema::hasColumn('storage_units', 'status_changed_at')) {
                $table->timestamp('status_changed_at')->nullable()->after('is_maintenance');
            }
            if (!Schema::hasColumn('storage_units', 'occupied_by_customer_id')) {
                $table->unsignedBigInteger('occupied_by_customer_id')->nullable()->after('status_changed_at');
            }
            if (!Schema::hasColumn('storage_units', 'active_contract_id')) {
                $table->unsignedBigInteger('active_contract_id')->nullable()->after('occupied_by_customer_id');
            }
        });

        // Normalize legacy free-text status values
        DB::table('storage_units')->orderBy('id')->chunkById(100, function ($units) {
            foreach ($units as $unit) {
                $raw = strtolower(trim((string) ($unit->status ?? '')));
                $normalized = 'vacant';
                if (in_array($raw, ['vacant', 'booked', 'occupied'], true)) {
                    $normalized = $raw;
                } elseif (strpos($raw, 'not paid') !== false || strpos($raw, 'not_paid') !== false) {
                    $normalized = 'booked but not paid';
                } elseif (strpos($raw, 'maintenance') !== false) {
                    $normalized = 'under maintenance';
                }

                $updates = ['status' => $normalized];
                if ($normalized === 'under maintenance') {
                    $updates['is_maintenance'] = 1;
                }
                DB::table('storage_units')->where('id', $unit->id)->update($updates);
            }
        });

        if (!Schema::hasTable('contract_storage_units')) {
            Schema::create('contract_storage_units', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('contract_id');
                $table->unsignedBigInteger('storage_unit_id');
                $table->string('unit_price')->nullable();
                $table->timestamp('released_at')->nullable();
                $table->timestamps();

                $table->foreign('contract_id')->references('id')->on('contracts')->onDelete('cascade');
                $table->foreign('storage_unit_id')->references('id')->on('storage_units')->onDelete('cascade');
                $table->unique(['contract_id', 'storage_unit_id'], 'csu_contract_unit_unique');
                $table->index(['storage_unit_id', 'released_at'], 'csu_unit_released_idx');
            });
        }

        if (!Schema::hasTable('storage_unit_status_logs')) {
            Schema::create('storage_unit_status_logs', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('storage_unit_id');
                $table->string('old_status')->nullable();
                $table->string('new_status');
                $table->string('trigger')->nullable();
                $table->string('reference_type')->nullable();
                $table->unsignedBigInteger('reference_id')->nullable();
                $table->unsignedBigInteger('user_id')->nullable();
                $table->text('notes')->nullable();
                $table->timestamps();

                $table->foreign('storage_unit_id')->references('id')->on('storage_units')->onDelete('cascade');
                $table->index(['storage_unit_id', 'created_at'], 'su_status_log_idx');
            });
        }

        if (!Schema::hasTable('storage_unit_assignments')) {
            Schema::create('storage_unit_assignments', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('storage_unit_id');
                $table->unsignedBigInteger('customer_id')->nullable();
                $table->string('hold_type', 16);
                $table->string('source_type');
                $table->unsignedBigInteger('source_id');
                $table->timestamp('released_at')->nullable();
                $table->unsignedBigInteger('hard_hold_key')->nullable();
                $table->timestamps();

                $table->foreign('storage_unit_id')->references('id')->on('storage_units')->onDelete('cascade');
                $table->index(['storage_unit_id', 'hold_type'], 'sua_unit_hold_idx');
                $table->index(['source_type', 'source_id'], 'sua_source_idx');
                $table->unique('hard_hold_key', 'sua_hard_hold_unique');
            });
        } else {
            // Partial create from failed prior run — ensure required columns/indexes exist
            Schema::table('storage_unit_assignments', function (Blueprint $table) {
                if (!Schema::hasColumn('storage_unit_assignments', 'hard_hold_key')) {
                    $table->unsignedBigInteger('hard_hold_key')->nullable();
                }
            });
        }

        // Backfill contract_storage_units from estimate pivots / legacy su_id
        $contracts = DB::table('contracts')
            ->where('is_deleted', 0)
            ->select('id', 'estimate_id', 'customer_id', 'created_at', 'updated_at')
            ->get();

        foreach ($contracts as $contract) {
            if (!$contract->estimate_id) {
                continue;
            }

            $estimateUnits = DB::table('estimate_storage_units')
                ->where('estimate_id', $contract->estimate_id)
                ->get();

            if ($estimateUnits->isEmpty()) {
                $estimate = DB::table('estimates')->where('id', $contract->estimate_id)->first();
                if ($estimate && $estimate->su_id) {
                    DB::table('contract_storage_units')->insertOrIgnore([
                        'contract_id' => $contract->id,
                        'storage_unit_id' => $estimate->su_id,
                        'unit_price' => $estimate->unit_price,
                        'released_at' => null,
                        'created_at' => $contract->created_at ?? now(),
                        'updated_at' => $contract->updated_at ?? now(),
                    ]);
                }
                continue;
            }

            foreach ($estimateUnits as $row) {
                DB::table('contract_storage_units')->insertOrIgnore([
                    'contract_id' => $contract->id,
                    'storage_unit_id' => $row->storage_unit_id,
                    'unit_price' => $row->unit_price,
                    'released_at' => null,
                    'created_at' => $contract->created_at ?? now(),
                    'updated_at' => $contract->updated_at ?? now(),
                ]);
            }
        }
    }

    public function down()
    {
        Schema::dropIfExists('storage_unit_assignments');
        Schema::dropIfExists('storage_unit_status_logs');
        Schema::dropIfExists('contract_storage_units');

        Schema::table('storage_units', function (Blueprint $table) {
            $cols = [];
            foreach (['active_contract_id', 'occupied_by_customer_id', 'status_changed_at', 'is_maintenance'] as $col) {
                if (Schema::hasColumn('storage_units', $col)) {
                    $cols[] = $col;
                }
            }
            if (!empty($cols)) {
                $table->dropColumn($cols);
            }
        });
    }
};
