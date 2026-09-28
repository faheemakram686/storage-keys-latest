<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePagesTable extends Migration
{
    /**
     * CMS content pages for MCP (additive — does not replace static Blade routes).
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pages', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->longText('content');
            $table->string('meta_title')->nullable();
            $table->string('meta_description', 512)->nullable();
            $table->boolean('status')->default(0);
            $table->boolean('is_deleted')->default(0);
            $table->timestamps();

            $table->index(['is_deleted', 'status']);
        });
    }

    /**
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pages');
    }
}
