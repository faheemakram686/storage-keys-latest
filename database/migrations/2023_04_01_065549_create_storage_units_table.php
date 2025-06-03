<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStorageUnitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('storage_units', function (Blueprint $table) {
            $table->id();
            $table->string('storage_unit_name');
            $table->unsignedBigInteger('wh_id');
            $table->unsignedBigInteger('stype_id');
            $table->integer('price');
            $table->string('location');
            $table->string('width');
            $table->string('length');
            $table->string('height');
            $table->string('status');
            $table->boolean('is_deleted')->default(0);
            $table->timestamps();
            $table->foreign('wh_id')->references('id')->on('warehouses');
            $table->foreign('stype_id')->references('id')->on('storage_types');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('storage_units');
    }
}
