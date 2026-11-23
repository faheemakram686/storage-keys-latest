<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * JSON-LD blocks for DB pages, saved by the MCP generate_schema tool (blogs already have schema_json).
 */
return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('pages', 'schema_json')) {
            Schema::table('pages', function (Blueprint $table) {
                $table->longText('schema_json')->nullable();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('pages', 'schema_json')) {
            Schema::table('pages', fn (Blueprint $table) => $table->dropColumn('schema_json'));
        }
    }
};
