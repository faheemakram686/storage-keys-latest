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
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->string('subject');
            $table->string('start_date');
            $table->string('due_date')->nullable()->default(null);
            $table->string('priority')->nullable()->default(null);
            $table->string('related_to')->nullable()->default(null);
            $table->string('related_id')->nullable()->default(null);
            $table->string('assignee')->nullable()->default(null);
            $table->string('follower')->nullable()->default(null);
            $table->text('description')->nullable()->default(null);
            $table->boolean('status');
            $table->boolean('is_deleted')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tasks');
    }
};
