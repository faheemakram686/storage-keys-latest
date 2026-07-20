<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('storage_units', function (Blueprint $table) {
            $table->string('width')->nullable()->change();
            $table->string('length')->nullable()->change();
            $table->string('height')->nullable()->change();
        });

        // Backfill empty size dimensions before enforcing NOT NULL.
        DB::table('storage_unit_sizes')
            ->where(function ($query) {
                $query->whereNull('width')->orWhere('width', '');
            })
            ->update(['width' => '0']);

        DB::table('storage_unit_sizes')
            ->where(function ($query) {
                $query->whereNull('length')->orWhere('length', '');
            })
            ->update(['length' => '0']);

        DB::table('storage_unit_sizes')
            ->where(function ($query) {
                $query->whereNull('height')->orWhere('height', '');
            })
            ->update(['height' => '0']);

        DB::statement('ALTER TABLE storage_unit_sizes MODIFY width VARCHAR(191) NOT NULL');
        DB::statement('ALTER TABLE storage_unit_sizes MODIFY length VARCHAR(191) NOT NULL');
        DB::statement('ALTER TABLE storage_unit_sizes MODIFY height VARCHAR(191) NOT NULL');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('storage_units', function (Blueprint $table) {
            $table->string('width')->nullable(false)->change();
            $table->string('length')->nullable(false)->change();
            $table->string('height')->nullable(false)->change();
        });

        Schema::table('storage_unit_sizes', function (Blueprint $table) {
            $table->string('width')->nullable()->change();
            $table->string('length')->nullable()->change();
            $table->string('height')->nullable()->change();
        });
    }
};
