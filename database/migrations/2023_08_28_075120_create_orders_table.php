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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email')->unique();
            $table->string('phone')->nullable()->default(null);
            $table->string('company_name')->nullable()->default(null);
            $table->string('company_address')->nullable()->default(null);
            $table->string('country')->nullable()->default(null);
            $table->string('address')->nullable()->default(null);
            $table->string('town')->nullable()->default(null);
            $table->string('city')->nullable()->default(null);
            $table->string('state')->nullable()->default(null);
            $table->string('zip')->nullable()->default(null);
            $table->text('notes')->nullable()->default(null);
            $table->string('payment_method')->nullable()->default(null);
            $table->integer('sub_amount')->nullable()->default(null);
            $table->integer('tax')->nullable()->default(null);
            $table->integer('total_amount')->nullable()->default(null);
            $table->boolean('status');
            $table->boolean('is_deleted')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
};
