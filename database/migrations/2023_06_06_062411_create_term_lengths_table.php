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
        Schema::create('term_lengths', function (Blueprint $table) {
            $table->id();
            $table->String('title')->nullable()->default(null);
            $table->integer('term_period')->nullable()->default(null);
            $table->double('discount_percentage')->nullable()->default(null);
            $table->text('description')->nullable()->default(null);
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
        Schema::dropIfExists('term_lengths');
    }
};
