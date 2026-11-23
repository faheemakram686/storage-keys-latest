<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * blogs.publish_at: a draft with a publish_at is published by `php artisan mcp:publish-scheduled` (every minute).
 * is_deleted on categories and tags: deletes through MCP go to the trash.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('blogs', 'publish_at')) {
            Schema::table('blogs', function (Blueprint $table) {
                $table->timestamp('publish_at')->nullable()->index();
            });
        }
        foreach (['blog_categories', 'blog_tags'] as $name) {
            if (Schema::hasTable($name) && !Schema::hasColumn($name, 'is_deleted')) {
                Schema::table($name, function (Blueprint $table) {
                    $table->boolean('is_deleted')->default(false);
                });
            }
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('blogs', 'publish_at')) {
            Schema::table('blogs', fn (Blueprint $table) => $table->dropColumn('publish_at'));
        }
        foreach (['blog_categories', 'blog_tags'] as $name) {
            if (Schema::hasColumn($name, 'is_deleted')) {
                Schema::table($name, fn (Blueprint $table) => $table->dropColumn('is_deleted'));
            }
        }
    }
};
