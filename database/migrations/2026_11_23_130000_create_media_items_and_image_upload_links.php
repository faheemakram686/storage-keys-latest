<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * media_items: images uploaded through MCP (and blog images registered later) with alt text and title.
 * image_upload_links: one-time browser upload pages the owner opens to add images.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('media_items')) {
            Schema::create('media_items', function (Blueprint $table) {
                $table->id();
                $table->string('path', 255)->unique(); // under storage/app/public, served at /storage/<path>
                $table->string('alt', 255)->nullable();
                $table->string('title', 255)->nullable();
                $table->unsignedInteger('width')->nullable();
                $table->unsignedInteger('height')->nullable();
                $table->unsignedInteger('bytes')->nullable();
                $table->string('mime', 60)->nullable();
                $table->string('source', 30)->default('mcp');
                $table->boolean('is_deleted')->default(false);
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('image_upload_links')) {
            Schema::create('image_upload_links', function (Blueprint $table) {
                $table->id();
                $table->string('token_hash', 64)->unique();
                $table->string('purpose', 255);
                $table->string('alt', 255)->nullable();
                $table->unsignedTinyInteger('max_files')->default(1);
                $table->json('media_ids')->nullable();
                $table->timestamp('expires_at');
                $table->timestamp('completed_at')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('image_upload_links');
        Schema::dropIfExists('media_items');
    }
};
