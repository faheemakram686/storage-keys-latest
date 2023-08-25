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
        Schema::create('estimates', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('su_id');
            $table->unsignedBigInteger('lead_id')->nullable()->default(null);
            $table->unsignedBigInteger('user_id');
            $table->Integer('customer_id')->nullable()->default(null);
            $table->Integer('addon_id')->nullable()->default(null);
            $table->Integer('email_template')->nullable()->default(null);
            $table->string('r_date')->nullable()->default(null);
            $table->string('company_name')->nullable();
            $table->string('lead_type')->nullable()->default(null);
            $table->string('f_name')->nullable()->default(null);
            $table->string('l_name')->nullable()->default(null);
            $table->string('email')->nullable()->default(null);
            $table->string('phone')->nullable()->default(null);
            $table->string('mobile1')->nullable()->default(null);
            $table->string('mobile2')->nullable()->default(null);
            $table->string('term_length')->nullable();
            $table->string('unit_price')->nullable()->default(null);
            $table->date('estimate_date')->nullable()->default(null);
            $table->date('expiry_date')->nullable()->default(null);
            $table->string('addon')->nullable();
            $table->string('insurence')->nullable();
            $table->string('goods')->nullable();
            $table->string('require_documents')->nullable();
            $table->boolean('status');
            $table->boolean('is_deleted')->default(0);
            $table->timestamps();
            $table->foreign('su_id')->references('id')->on('storage_units');
            $table->foreign('lead_id')->references('id')->on('leads');
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('estimates');
    }
};
