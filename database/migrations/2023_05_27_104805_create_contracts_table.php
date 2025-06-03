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
        Schema::create('contracts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('customer_id');
            $table->unsignedBigInteger('estimate_id');
            $table->unsignedBigInteger('template_id')->nullable();
            $table->unsignedBigInteger('user_id');
            $table->String('subject')->nullable()->default(null);
            $table->String('contract_value')->nullable()->default(null);
            $table->String('contract_type')->nullable()->default(null);
            $table->String('start_date')->nullable()->default(null);
            $table->String('end_date')->nullable()->default(null);
            $table->text('description')->nullable()->default(null);
            $table->text('path')->nullable()->default(null);
            $table->text('sign_image')->nullable()->default(null);
            $table->boolean('status');
            $table->boolean('is_accepted');
            $table->boolean('is_deleted')->default(0);
            $table->boolean('is_signed')->default(0);
            $table->timestamps();
            $table->foreign('customer_id')->references('id')->on('customers');
            $table->foreign('template_id')->references('id')->on('contract_templates');
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
        Schema::dropIfExists('contracts');
    }
};
