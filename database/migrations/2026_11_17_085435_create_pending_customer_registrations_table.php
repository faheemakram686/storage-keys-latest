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
        Schema::create('pending_customer_registrations', function (Blueprint $table) {
            $table->id();
            $table->string('verification_token', 64)->unique();
            $table->string('email')->index();
            $table->text('registration_data'); // Encrypted JSON of registration data
            $table->string('otp_hash');
            $table->timestamp('expires_at')->nullable();
            $table->timestamp('last_sent_at')->nullable();
            $table->unsignedInteger('resend_count')->default(0);
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
        Schema::dropIfExists('pending_customer_registrations');
    }
};
