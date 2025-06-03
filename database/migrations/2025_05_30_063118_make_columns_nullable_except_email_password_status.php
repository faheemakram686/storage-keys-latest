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
        Schema::table('users', function (Blueprint $table) {
            $table->string('first_name')->nullable()->default(null)->change();
            $table->string('last_name')->nullable()->default(null)->change();
            $table->string('avatar')->nullable()->default(null)->change();
            $table->string('phone')->nullable()->default(null)->change();
            $table->string('address')->nullable()->default(null)->change();
            $table->unsignedBigInteger('user_type')->nullable()->default(null)->change();
            $table->timestamp('last_login_at')->nullable()->default(null)->change();
            $table->foreignId('status_id')->nullable()->default(null)->change();
            $table->string('invitation_token')->nullable()->default(null)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('first_name')->nullable(false)->default('')->change();
            $table->string('last_name')->nullable(false)->default('')->change();
            $table->string('avatar')->nullable(false)->default('')->change();
            $table->string('phone')->nullable(false)->default('')->change();
            $table->string('address')->nullable(false)->default('')->change();
            $table->unsignedBigInteger('user_type')->nullable(false)->default(0)->change();
            $table->timestamp('last_login_at')->nullable(false)->default(now())->change();
            $table->foreignId('status_id')->nullable(false)->default(1)->change(); // use correct default if needed
            $table->string('invitation_token')->nullable(false)->default('')->change();
        });
    }
};
