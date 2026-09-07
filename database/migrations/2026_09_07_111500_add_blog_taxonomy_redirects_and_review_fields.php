<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBlogTaxonomyRedirectsAndReviewFields extends Migration
{
    public function up()
    {
        if (!Schema::hasColumn('blogs', 'last_reviewed_at')) {
            Schema::table('blogs', function (Blueprint $table) {
                $table->timestamp('last_reviewed_at')->nullable()->after('updated_at');
            });
        }

        if (!Schema::hasTable('blog_categories')) {
            Schema::create('blog_categories', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('slug')->unique();
                $table->text('description')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('blog_tags')) {
            Schema::create('blog_tags', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('slug')->unique();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('blog_category_blog')) {
            Schema::create('blog_category_blog', function (Blueprint $table) {
                $table->unsignedBigInteger('blog_id');
                $table->unsignedBigInteger('blog_category_id');
                $table->primary(['blog_id', 'blog_category_id']);
            });
        }

        if (!Schema::hasTable('blog_tag_blog')) {
            Schema::create('blog_tag_blog', function (Blueprint $table) {
                $table->unsignedBigInteger('blog_id');
                $table->unsignedBigInteger('blog_tag_id');
                $table->primary(['blog_id', 'blog_tag_id']);
            });
        }

        if (!Schema::hasTable('url_redirects')) {
            Schema::create('url_redirects', function (Blueprint $table) {
                $table->id();
                $table->string('from_path', 512)->unique();
                $table->string('to_url', 2048);
                $table->unsignedSmallInteger('status_code')->default(301);
                $table->boolean('is_active')->default(1);
                $table->timestamps();
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('blog_tag_blog');
        Schema::dropIfExists('blog_category_blog');
        Schema::dropIfExists('blog_tags');
        Schema::dropIfExists('blog_categories');
        Schema::dropIfExists('url_redirects');

        if (Schema::hasColumn('blogs', 'last_reviewed_at')) {
            Schema::table('blogs', function (Blueprint $table) {
                $table->dropColumn('last_reviewed_at');
            });
        }
    }
}
