<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('storage_unit_assignments')) {
            return;
        }

        if (!Schema::hasColumn('storage_unit_assignments', 'hard_hold_key')) {
            Schema::table('storage_unit_assignments', function (Blueprint $table) {
                $table->unsignedBigInteger('hard_hold_key')->nullable();
            });
        }

        $exists = collect(DB::select("SHOW INDEX FROM storage_unit_assignments WHERE Key_name = 'sua_hard_hold_unique'"))->isNotEmpty();
        if (!$exists) {
            // Clear duplicate hard_hold_keys before adding unique (keep lowest id).
            $dupes = DB::table('storage_unit_assignments')
                ->select('hard_hold_key', DB::raw('MIN(id) as keep_id'))
                ->whereNotNull('hard_hold_key')
                ->groupBy('hard_hold_key')
                ->havingRaw('COUNT(*) > 1')
                ->get();

            foreach ($dupes as $dupe) {
                DB::table('storage_unit_assignments')
                    ->where('hard_hold_key', $dupe->hard_hold_key)
                    ->where('id', '!=', $dupe->keep_id)
                    ->update([
                        'hard_hold_key' => null,
                        'released_at' => DB::raw('COALESCE(released_at, NOW())'),
                    ]);
            }

            Schema::table('storage_unit_assignments', function (Blueprint $table) {
                $table->unique('hard_hold_key', 'sua_hard_hold_unique');
            });
        }
    }

    public function down()
    {
        $exists = collect(DB::select("SHOW INDEX FROM storage_unit_assignments WHERE Key_name = 'sua_hard_hold_unique'"))->isNotEmpty();
        if ($exists) {
            Schema::table('storage_unit_assignments', function (Blueprint $table) {
                $table->dropUnique('sua_hard_hold_unique');
            });
        }
    }
};
