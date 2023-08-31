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
            $table->unsignedBigInteger('customer_id');
            $table->text('notes')->nullable()->default(null);
            $table->string('payment_method')->nullable()->default(null);
            $table->integer('sub_amount')->nullable()->default(null);
            $table->integer('tax')->nullable()->default(null);
            $table->integer('total_amount')->nullable()->default(null);
            $table->boolean('status');
            $table->boolean('is_deleted')->default(0);
            $table->timestamps();
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');
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
