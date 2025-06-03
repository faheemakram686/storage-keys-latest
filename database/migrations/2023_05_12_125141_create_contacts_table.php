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
        Schema::create('contacts', function (Blueprint $table) {
            $table->id();
            $table->integer('q_customer_id')->nullable()->unique();
            $table->unsignedBigInteger('customer_id');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('position')->nullable()->default(null);
            $table->string('email')->unique();
            $table->string('phone')->nullable()->default(null);
            $table->string('password')->nullable()->default(null);
            $table->string('contact_type')->nullable()->default(null);
            $table->string('profile_pic')->nullable()->default(null);
            $table->string('remember_token')->nullable()->default(null);
            $table->boolean('status');
            $table->boolean('is_deleted')->default(0);
            $table->timestamps();
            $table->foreign('customer_id')->references('id')->on('customers');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('contacts');
    }
};
