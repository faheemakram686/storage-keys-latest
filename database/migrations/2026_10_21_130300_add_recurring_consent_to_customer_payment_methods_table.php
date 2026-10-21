<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('customer_payment_methods', function (Blueprint $table) {
            $table->timestamp('recurring_consent_at')->nullable()->after('is_active');
        });
    }

    public function down()
    {
        Schema::table('customer_payment_methods', function (Blueprint $table) {
            $table->dropColumn('recurring_consent_at');
        });
    }
};
