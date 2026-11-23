<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * One row per change to website content (pages, blogs, sections, FAQs, ...).
 * Serves both the MCP activity_log tool and revision restore: before/after hold full snapshots.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('content_revisions')) {
            return;
        }

        Schema::create('content_revisions', function (Blueprint $table) {
            $table->id();
            $table->string('entity_type', 40);
            $table->unsignedBigInteger('entity_id');
            $table->string('event', 30); // created, updated, trashed, restored, purged, reverted
            $table->string('source', 20)->default('admin'); // mcp, admin, console
            $table->string('tool', 60)->nullable();
            $table->string('summary', 255)->nullable();
            $table->json('changed')->nullable();
            $table->longText('before')->nullable();
            $table->longText('after')->nullable();
            $table->timestamp('created_at')->nullable();

            $table->index(['entity_type', 'entity_id']);
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('content_revisions');
    }
};
