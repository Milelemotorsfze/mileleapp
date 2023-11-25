<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('employee_hiring_requests', function (Blueprint $table) {
            $table->dropForeign(['department_id']);
            $table->dropColumn('department_id');
        });
        Schema::table('emp_profile', function (Blueprint $table) {
            $table->dropForeign(['department_id']);
            $table->dropColumn('department_id');
        });
        Schema::table('employee_hiring_questionnaires', function (Blueprint $table) {
            $table->dropForeign(['department_id']);
            $table->dropColumn('department_id');
        });
        Schema::table('job_descriptions', function (Blueprint $table) {
            $table->dropForeign(['department_id']);
            $table->dropColumn('department_id');
        });
        Schema::table('joining_reports', function (Blueprint $table) {
            $table->dropForeign(['transfer_from_department_id']);
            $table->dropColumn('transfer_from_department_id');
            $table->dropForeign(['transfer_to_department_id']);
            $table->dropColumn('transfer_to_department_id');
        });
        Schema::rename('master_deparments', 'master_departments');
    }  
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employee_hiring_requests', function (Blueprint $table) {
            $table->bigInteger('department_id')->unsigned()->index()->nullable();
            $table->foreign('department_id')->references('id')->on('master_deparments')->onDelete('cascade');
        });
        Schema::table('emp_profile', function (Blueprint $table) {
            $table->bigInteger('department_id')->unsigned()->index()->nullable()->after('designation_id');
            $table->foreign('department_id')->references('id')->on('master_deparments')->onDelete('cascade');
        });
        Schema::table('employee_hiring_questionnaires', function (Blueprint $table) {
            $table->bigInteger('department_id')->unsigned()->index()->nullable();
            $table->foreign('department_id')->references('id')->on('master_deparments')->onDelete('cascade');
        });
        Schema::table('job_descriptions', function (Blueprint $table) {
            $table->bigInteger('department_id')->unsigned()->index()->nullable();
            $table->foreign('department_id')->references('id')->on('master_deparments')->onDelete('cascade');
        });
        Schema::table('joining_reports', function (Blueprint $table) {
            $table->bigInteger('transfer_from_department_id')->unsigned()->index()->nullable()->after('permanent_joining_location_id');
            $table->foreign('transfer_from_department_id')->references('id')->on('master_deparments')->onDelete('cascade');
            $table->bigInteger('transfer_to_department_id')->unsigned()->index()->nullable()->after('transfer_from_location_id');
            $table->foreign('transfer_to_department_id')->references('id')->on('master_deparments')->onDelete('cascade');
        });
        Schema::rename('master_departments', 'master_deparments');
    }
};
