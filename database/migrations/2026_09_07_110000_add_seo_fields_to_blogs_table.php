<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSeoFieldsToBlogsTable extends Migration
{
    public function up()
    {
        Schema::table('blogs', function (Blueprint $table) {
            if (!Schema::hasColumn('blogs', 'meta_title')) {
                $table->string('meta_title', 255)->nullable()->after('slug');
            }
            if (!Schema::hasColumn('blogs', 'meta_description')) {
                $table->string('meta_description', 512)->nullable()->after('meta_title');
            }
            if (!Schema::hasColumn('blogs', 'canonical_url')) {
                $table->string('canonical_url', 2048)->nullable()->after('meta_description');
            }
            if (!Schema::hasColumn('blogs', 'robots')) {
                $table->string('robots', 64)->nullable()->after('canonical_url');
            }
            if (!Schema::hasColumn('blogs', 'schema_json')) {
                $table->longText('schema_json')->nullable()->after('robots');
            }
        });
    }

    public function down()
    {
        Schema::table('blogs', function (Blueprint $table) {
            foreach (['meta_title', 'meta_description', 'canonical_url', 'robots', 'schema_json'] as $col) {
                if (Schema::hasColumn('blogs', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
}
