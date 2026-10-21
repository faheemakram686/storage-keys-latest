<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('customer_payment_methods', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('customer_id');
            $table->text('card_token');
            $table->string('masked_pan')->nullable();
            $table->string('expiry', 7)->nullable();
            $table->string('scheme')->nullable();
            $table->string('cardholder_name')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');
            $table->index(['customer_id', 'is_active']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('customer_payment_methods');
    }
};
