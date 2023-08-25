<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
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
            $table->unsignedBigInteger('slevel_id');
            $table->unsignedBigInteger('ssize_id');
            $table->foreign('slevel_id')->references('id')->on('storage_unit_levels');
            $table->foreign('ssize_id')->references('id')->on('storage_unit_sizes');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('storage_units', function (Blueprint $table) {
            //
        });
    }
};
