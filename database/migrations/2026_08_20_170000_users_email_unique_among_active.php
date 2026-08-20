<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class UsersEmailUniqueAmongActive extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropUnique('users_email_unique');
        });

        // Soft-deleted rows keep the original email for history, but only active
        // (deleted_at IS NULL) emails remain unique. MySQL/MariaDB allow multiple
        // NULLs in a unique index, so deleted rows do not collide.
        DB::statement("
            ALTER TABLE users
            ADD COLUMN email_unique_active VARCHAR(160)
            GENERATED ALWAYS AS (IF(deleted_at IS NULL, email, NULL)) STORED
        ");

        DB::statement('CREATE UNIQUE INDEX users_email_unique_active ON users (email_unique_active)');
    }

    public function down()
    {
        DB::statement('DROP INDEX users_email_unique_active ON users');
        DB::statement('ALTER TABLE users DROP COLUMN email_unique_active');

        Schema::table('users', function (Blueprint $table) {
            $table->unique('email');
        });
    }
}
