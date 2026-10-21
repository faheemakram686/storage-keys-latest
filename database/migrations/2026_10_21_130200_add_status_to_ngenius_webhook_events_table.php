<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('ngenius_webhook_events', function (Blueprint $table) {
            $table->string('status', 32)->default('received')->after('order_reference');
            $table->timestamp('processed_at')->nullable()->after('status');
        });
    }

    public function down()
    {
        Schema::table('ngenius_webhook_events', function (Blueprint $table) {
            $table->dropColumn(['status', 'processed_at']);
        });
    }
};
