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
        Schema::create('leads', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('su_id');
            $table->Integer('customer_id')->nullable()->default(null);
            $table->unsignedBigInteger('user_res_id');
            $table->string('r_date');
            $table->string('company_name')->nullable()->default(null);
            $table->string('lead_type');
            $table->string('lead_rating');
            $table->Integer('lead_source');
            $table->string('f_name');
            $table->string('l_name');
            $table->string('email');
            $table->string('phone');
            $table->string('mobile1');
            $table->string('mobile2');
            $table->string('price')->nullable();
            $table->string('addon')->nullable();
            $table->string('insurence')->nullable();
            $table->string('goods')->nullable();
            $table->string('term')->nullable()->default(null);
            $table->boolean('status');
            $table->boolean('is_deleted')->default(0);
            $table->timestamps();
            $table->foreign('su_id')->references('id')->on('storage_units');
            $table->foreign('user_res_id')->references('id')->on('users');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('leads');
    }
};
