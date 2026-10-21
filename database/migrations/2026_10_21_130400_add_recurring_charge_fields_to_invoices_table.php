<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->unsignedTinyInteger('recurring_charge_attempts')->default(0)->after('invoice_ref');
            $table->timestamp('last_recurring_charge_at')->nullable()->after('recurring_charge_attempts');
        });
    }

    public function down()
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropColumn(['recurring_charge_attempts', 'last_recurring_charge_at']);
        });
    }
};
