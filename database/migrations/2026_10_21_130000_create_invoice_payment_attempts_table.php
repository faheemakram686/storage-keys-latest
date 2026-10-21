<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('invoice_payment_attempts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('invoice_id');
            $table->string('order_reference')->unique();
            $table->decimal('expected_amount', 10, 2);
            $table->string('currency', 3)->default('AED');
            $table->string('state', 32)->default('created');
            $table->string('source', 32)->default('hosted');
            $table->boolean('save_card')->default(false);
            $table->unsignedTinyInteger('retry_count')->default(0);
            $table->timestamps();

            $table->foreign('invoice_id')->references('id')->on('invoices')->onDelete('cascade');
            $table->index(['invoice_id', 'state']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('invoice_payment_attempts');
    }
};
