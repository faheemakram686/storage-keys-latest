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
        Schema::create('lead_storage_units', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('lead_id');
            $table->unsignedBigInteger('storage_unit_id');
            $table->timestamps();

            $table->foreign('lead_id')->references('id')->on('leads')->onDelete('cascade');
            $table->foreign('storage_unit_id')->references('id')->on('storage_units')->onDelete('cascade');
            $table->unique(['lead_id', 'storage_unit_id']);
        });

        Schema::create('estimate_storage_units', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('estimate_id');
            $table->unsignedBigInteger('storage_unit_id');
            $table->string('unit_price')->nullable();
            $table->timestamps();

            $table->foreign('estimate_id')->references('id')->on('estimates')->onDelete('cascade');
            $table->foreign('storage_unit_id')->references('id')->on('storage_units')->onDelete('cascade');
            $table->unique(['estimate_id', 'storage_unit_id']);
        });

        // Backfill from existing su_id columns
        $leads = DB::table('leads')->whereNotNull('su_id')->select('id', 'su_id', 'created_at', 'updated_at')->get();
        foreach ($leads as $lead) {
            DB::table('lead_storage_units')->insertOrIgnore([
                'lead_id' => $lead->id,
                'storage_unit_id' => $lead->su_id,
                'created_at' => $lead->created_at ?? now(),
                'updated_at' => $lead->updated_at ?? now(),
            ]);
        }

        $estimates = DB::table('estimates')->whereNotNull('su_id')->select('id', 'su_id', 'unit_price', 'created_at', 'updated_at')->get();
        foreach ($estimates as $estimate) {
            DB::table('estimate_storage_units')->insertOrIgnore([
                'estimate_id' => $estimate->id,
                'storage_unit_id' => $estimate->su_id,
                'unit_price' => $estimate->unit_price,
                'created_at' => $estimate->created_at ?? now(),
                'updated_at' => $estimate->updated_at ?? now(),
            ]);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('estimate_storage_units');
        Schema::dropIfExists('lead_storage_units');
    }
};
