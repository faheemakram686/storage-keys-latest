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
        Schema::create('lead_models', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('su_id');
            $table->string('r_date');
            $table->string('company_name')->nullable();
            $table->string('lead_type');
            $table->string('f_name');
            $table->string('l_name');
            $table->string('email');
            $table->string('phone');
            $table->string('mobile1');
            $table->string('mobile2');
            $table->string('price')->nullable();
            $table->string('addon')->nullable();
            $table->string('insurence')->nullable();
            $table->string('goods');
            $table->string('term');
            $table->boolean('status');
            $table->boolean('is_deleted')->default(0);
            $table->timestamps();
            $table->foreign('su_id')->references('id')->on('storage_units');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('lead_models');
    }
};
