<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
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
        $exists = DB::table('app_settings')->where('key', 'approval_mode')->exists();
        if (!$exists) {
            DB::table('app_settings')->insert([
                'key' => 'approval_mode',
                'value' => 'multi_level',
                'description' => 'Approval workflow mode: multi_level or direct',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        Schema::table('leads', function (Blueprint $table) {
            if (!Schema::hasColumn('leads', 'approval_status')) {
                $table->unsignedTinyInteger('approval_status')->default(0)->after('status');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::table('app_settings')->where('key', 'approval_mode')->delete();

        Schema::table('leads', function (Blueprint $table) {
            if (Schema::hasColumn('leads', 'approval_status')) {
                $table->dropColumn('approval_status');
            }
        });
    }
};
