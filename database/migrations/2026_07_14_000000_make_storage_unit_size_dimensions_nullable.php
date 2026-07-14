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
        Schema::table('storage_unit_sizes', function (Blueprint $table) {
            $table->string('width')->nullable()->change();
            $table->string('length')->nullable()->change();
            $table->string('height')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('storage_unit_sizes', function (Blueprint $table) {
            $table->string('width')->nullable(false)->change();
            $table->string('length')->nullable(false)->change();
            $table->string('height')->nullable(false)->change();
        });
    }
};
