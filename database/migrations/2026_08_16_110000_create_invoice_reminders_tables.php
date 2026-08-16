<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('invoice_reminders', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->boolean('is_enabled')->default(1);
            $table->unsignedInteger('trigger_days')->default(0);
            $table->string('trigger_relation')->default('before');
            $table->string('recipient_type')->default('customer');
            $table->unsignedBigInteger('from_user_id')->nullable();
            $table->json('cc_emails')->nullable();
            $table->json('bcc_emails')->nullable();
            $table->text('subject')->nullable();
            $table->longText('body')->nullable();
            $table->boolean('status')->default(1);
            $table->boolean('is_deleted')->default(0);
            $table->timestamps();

            $table->foreign('from_user_id')->references('id')->on('users')->nullOnDelete();
        });

        Schema::create('invoice_reminder_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('invoice_id');
            $table->unsignedBigInteger('invoice_reminder_id');
            $table->string('recipient_email')->nullable();
            $table->timestamp('sent_at')->nullable();
            $table->timestamps();

            $table->unique(['invoice_id', 'invoice_reminder_id'], 'invoice_reminder_once');
            $table->foreign('invoice_id')->references('id')->on('invoices')->cascadeOnDelete();
            $table->foreign('invoice_reminder_id')->references('id')->on('invoice_reminders')->cascadeOnDelete();
        });

        Schema::table('customers', function (Blueprint $table) {
            $table->boolean('invoice_reminders_enabled')->default(0)->after('status');
        });
    }

    public function down()
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->dropColumn('invoice_reminders_enabled');
        });

        Schema::dropIfExists('invoice_reminder_logs');
        Schema::dropIfExists('invoice_reminders');
    }
};
