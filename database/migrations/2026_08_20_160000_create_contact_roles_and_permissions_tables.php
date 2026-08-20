<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('contact_roles', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('alias')->unique();
            $table->string('description')->nullable();
            $table->timestamps();
        });

        Schema::create('contact_permissions', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('group_name')->nullable();
            $table->string('description')->nullable();
            $table->timestamps();
        });

        Schema::create('contact_role_permission', function (Blueprint $table) {
            $table->unsignedBigInteger('contact_role_id');
            $table->unsignedBigInteger('contact_permission_id');
            $table->primary(['contact_role_id', 'contact_permission_id'], 'contact_role_permission_primary');
            $table->foreign('contact_role_id')->references('id')->on('contact_roles')->onDelete('cascade');
            $table->foreign('contact_permission_id', 'crp_permission_fk')->references('id')->on('contact_permissions')->onDelete('cascade');
        });

        Schema::table('contacts', function (Blueprint $table) {
            $table->unsignedBigInteger('contact_role_id')->nullable()->after('contact_type');
            $table->foreign('contact_role_id')->references('id')->on('contact_roles')->nullOnDelete();
        });
    }

    public function down()
    {
        Schema::table('contacts', function (Blueprint $table) {
            $table->dropForeign(['contact_role_id']);
            $table->dropColumn('contact_role_id');
        });
        Schema::dropIfExists('contact_role_permission');
        Schema::dropIfExists('contact_permissions');
        Schema::dropIfExists('contact_roles');
    }
};
