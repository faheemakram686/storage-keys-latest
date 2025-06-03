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
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->integer('q_customer_id')->nullable()->unique();
            $table->unsignedBigInteger('lead_id')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->string('customer_type')->nullable()->default(null);
            $table->string('company_name')->nullable()->default(null);
            $table->string('customer_name')->nullable()->default(null);
            $table->string('license_no')->nullable()->default(null);
            $table->string('vat')->nullable()->default(null);
            $table->string('customer_id_card')->nullable()->default(null);
            $table->string('passport_no')->nullable()->default(null);
            $table->string('dob')->nullable()->default(null);
            $table->string('email')->nullable()->default(null);
            $table->string('phone')->nullable()->default(null);
            $table->string('mobile')->nullable()->default(null);
            $table->string('home')->nullable()->default(null);
            $table->text('address')->nullable()->default(null);
            $table->string('country')->nullable()->default(null);
            $table->string('state')->nullable()->default(null);
            $table->string('city')->nullable()->default(null);
            $table->boolean('status');
            $table->boolean('is_deleted')->default(0);
            $table->timestamps();
            $table->foreign('lead_id')->references('id')->on('leads');
            $table->foreign('created_by')->references('id')->on('users');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('customers');
    }
};
