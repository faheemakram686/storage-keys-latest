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
        Schema::table('invoices', function (Blueprint $table) {
            $table->date('next_recurring_date')->nullable()->after('duration_type')->index();
            $table->unsignedBigInteger('parent_invoice_id')->nullable()->after('next_recurring_date');
            $table->foreign('parent_invoice_id')->references('id')->on('invoices')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropForeign(['parent_invoice_id']);
            $table->dropColumn(['next_recurring_date', 'parent_invoice_id']);
        });
    }
};
