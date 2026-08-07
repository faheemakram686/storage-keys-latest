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
        Schema::table('profiles', function (Blueprint $table) {
            $table->text('res_visa_loc')->nullable()->change();
            $table->text('emirate_id')->nullable()->change();
            $table->integer('notice_period')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('profiles', function (Blueprint $table) {
            $table->text('res_visa_loc')->nullable(false)->change();
            $table->text('emirate_id')->nullable(false)->change();
            $table->integer('notice_period')->nullable(false)->change();
        });
    }
};
