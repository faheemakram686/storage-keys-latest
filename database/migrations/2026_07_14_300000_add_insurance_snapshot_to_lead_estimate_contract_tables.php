<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddInsuranceSnapshotToLeadEstimateContractTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('leads', function (Blueprint $table) {
            $table->unsignedBigInteger('insurance_id')->nullable()->after('insurence');
            $table->decimal('insurance_amount', 12, 2)->nullable()->after('insurance_id');
            $table->string('insurance_cover')->nullable()->after('insurance_amount');
            $table->foreign('insurance_id')->references('id')->on('insurances')->nullOnDelete();
        });

        Schema::table('estimates', function (Blueprint $table) {
            $table->unsignedBigInteger('insurance_id')->nullable()->after('insurence');
            $table->decimal('insurance_amount', 12, 2)->nullable()->after('insurance_id');
            $table->string('insurance_cover')->nullable()->after('insurance_amount');
            $table->foreign('insurance_id')->references('id')->on('insurances')->nullOnDelete();
        });

        Schema::table('contracts', function (Blueprint $table) {
            $table->unsignedBigInteger('insurance_id')->nullable()->after('contract_value');
            $table->decimal('insurance_amount', 12, 2)->nullable()->after('insurance_id');
            $table->string('insurance_cover')->nullable()->after('insurance_amount');
            $table->string('insurence')->nullable()->after('insurance_cover');
            $table->foreign('insurance_id')->references('id')->on('insurances')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('leads', function (Blueprint $table) {
            $table->dropForeign(['insurance_id']);
            $table->dropColumn(['insurance_id', 'insurance_amount', 'insurance_cover']);
        });

        Schema::table('estimates', function (Blueprint $table) {
            $table->dropForeign(['insurance_id']);
            $table->dropColumn(['insurance_id', 'insurance_amount', 'insurance_cover']);
        });

        Schema::table('contracts', function (Blueprint $table) {
            $table->dropForeign(['insurance_id']);
            $table->dropColumn(['insurance_id', 'insurance_amount', 'insurance_cover', 'insurence']);
        });
    }
}
