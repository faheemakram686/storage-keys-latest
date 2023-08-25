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
        Schema::create('storage_unit_sizes', function (Blueprint $table) {
            $table->id();
            $table->string('unit_type_name');
            $table->unsignedBigInteger('measurement_unit');
            $table->string('width');
            $table->string('length');
            $table->string('height');
            $table->string('no_of_units');
            $table->boolean('status');
            $table->boolean('is_deleted')->default(0);
            $table->timestamps();
            $table->foreign('measurement_unit')->references('id')->on('measurement_units');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('storage_unit_sizes');
    }
};
