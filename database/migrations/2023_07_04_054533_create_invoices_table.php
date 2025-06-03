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
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('customer_id');
            $table->unsignedBigInteger('contract_id')->nullable()->default(null);
//            $table->unsignedBigInteger('order_id')->nullable()->default(null);
            $table->unsignedBigInteger('user_id')->nullable()->default(null);
            $table->string('type');
            $table->string('recurring')->nullable()->default(null);
            $table->string('no_cycle')->nullable()->default(null);
            $table->string('duration')->nullable()->default(null);
            $table->string('duration_type')->nullable()->default(null);
            $table->string('invoice_no')->nullable()->default(null);
            $table->date('invoice_date')->nullable()->default(null);
            $table->date('due_date')->nullable()->default(null);
            $table->double('sub_total')->nullable()->default(null);
            $table->double('vat')->nullable()->default(null);
            $table->double('grand_total')->nullable()->default(null);
            $table->text('note')->nullable()->default(null);
            $table->text('payment_method')->nullable()->default(null);
            $table->boolean('status');
            $table->boolean('is_deleted')->default(0);
            $table->boolean('payment_status')->default(0);
            $table->timestamps();
            $table->foreign('customer_id')->references('id')->on('customers');
            $table->foreign('contract_id')->references('id')->on('contracts');
//            $table->foreign('order_id')->references('id')->on('orders');
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
        Schema::dropIfExists('invoices');
    }
};
