<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('leads', function (Blueprint $table) {
            $table->string('alt_contact_name')->nullable()->after('mobile2');
            $table->string('alt_contact_email')->nullable()->after('alt_contact_name');
            $table->string('alt_contact_mobile')->nullable()->after('alt_contact_email');
        });

        Schema::table('estimates', function (Blueprint $table) {
            $table->string('alt_contact_name')->nullable()->after('mobile2');
            $table->string('alt_contact_email')->nullable()->after('alt_contact_name');
            $table->string('alt_contact_mobile')->nullable()->after('alt_contact_email');
        });

        Schema::table('contracts', function (Blueprint $table) {
            $table->string('alt_contact_name')->nullable()->after('description');
            $table->string('alt_contact_email')->nullable()->after('alt_contact_name');
            $table->string('alt_contact_mobile')->nullable()->after('alt_contact_email');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('leads', function (Blueprint $table) {
            $table->dropColumn(['alt_contact_name', 'alt_contact_email', 'alt_contact_mobile']);
        });

        Schema::table('estimates', function (Blueprint $table) {
            $table->dropColumn(['alt_contact_name', 'alt_contact_email', 'alt_contact_mobile']);
        });

        Schema::table('contracts', function (Blueprint $table) {
            $table->dropColumn(['alt_contact_name', 'alt_contact_email', 'alt_contact_mobile']);
        });
    }
};
