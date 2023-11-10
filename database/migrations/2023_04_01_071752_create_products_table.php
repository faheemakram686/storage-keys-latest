<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->integer('q_product_id')->nullable()->unique();
            $table->string('p_name')->nullable();
            $table->string('detail')->nullable();
            $table->float('pur_price')->nullable();
            $table->float('sell_price')->nullable();
            $table->string('disc_type')->nullable();
            $table->string('disc_amount')->nullable();
            $table->string('qty')->nullable();
            $table->string('image')->nullable();
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
        Schema::dropIfExists('products');
    }
}
