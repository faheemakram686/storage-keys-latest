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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('customer_id');
            $table->unsignedBigInteger('contract_id')->nullable();
//            $table->unsignedBigInteger('order_id')->nullable();
            $table->unsignedBigInteger('invoice_id');
            $table->date('payment_date')->nullable()->default(null);
            $table->decimal('amount_received', 10, 2)->nullable();
            $table->integer('payment_method')->nullable()->default(null);
            $table->string('note')->nullable()->default(null);
            $table->boolean('status');
            $table->boolean('is_deleted')->default(0);
            $table->timestamps();
            $table->foreign('invoice_id')->references('id')->on('invoices')->onDelete('cascade');
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');
            $table->foreign('contract_id')->references('id')->on('contracts')->onDelete('cascade');
//            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
        });
    }



    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payments');
    }
};
