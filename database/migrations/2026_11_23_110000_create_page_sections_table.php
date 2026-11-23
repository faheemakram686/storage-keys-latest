<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Sections of DB (CMS) pages. pages.content stays what the site renders: App\Services\Mcp\Sections
 * rebuilds it from the visible sections after every section change.
 *
 * Backfill: every existing page gets one html section holding its current content, so the
 * rendered output does not change.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('page_sections')) {
            Schema::create('page_sections', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('page_id');
                $table->unsignedInteger('position')->default(1);
                $table->string('type', 20)->default('html'); // html | component
                $table->string('name', 120)->nullable();
                $table->string('component', 60)->nullable();
                $table->json('attrs')->nullable();
                $table->longText('html')->nullable();
                $table->boolean('is_visible')->default(true);
                $table->boolean('is_deleted')->default(false);
                $table->timestamps();

                $table->index(['page_id', 'is_deleted', 'position']);
                $table->foreign('page_id')->references('id')->on('pages')->cascadeOnDelete();
            });
        }

        $now = now();
        $pages = DB::table('pages')->whereNotExists(function ($q) {
            $q->select(DB::raw(1))->from('page_sections')->whereColumn('page_sections.page_id', 'pages.id');
        })->get(['id', 'content']);

        foreach ($pages as $page) {
            if (trim((string) $page->content) === '') {
                continue;
            }
            DB::table('page_sections')->insert([
                'page_id' => $page->id,
                'position' => 1,
                'type' => 'html',
                'name' => 'Main content',
                'html' => $page->content,
                'is_visible' => true,
                'is_deleted' => false,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('page_sections');
    }
};
