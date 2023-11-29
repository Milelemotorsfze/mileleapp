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
            $table->dropForeign(['reporting_to']);
            $table->dropColumn('reporting_to');
        });
        Schema::table('job_descriptions', function (Blueprint $table) {
            $table->dropForeign(['job_title']);
            $table->dropColumn('job_title');
            $table->dropForeign(['reporting_to']);
            $table->dropColumn('reporting_to');
            $table->dropForeign(['department_id']);
            $table->dropColumn('department_id');
        });
        Schema::table('interview_summary_reports', function (Blueprint $table) {
            $table->dropColumn('action_by_department_head');
            $table->dropForeign(['department_head_id']);
            $table->dropColumn('department_head_id');
            $table->dropColumn('department_head_action_at');
            $table->dropColumn('comments_by_department_head');
            $table->enum('action_by_hr_manager', ['pending', 'approved','rejected'])->nullable();
            $table->bigInteger('hr_manager_id')->unsigned()->index()->nullable();
            $table->foreign('hr_manager_id')->references('id')->on('users')->onDelete('cascade');
            $table->timestamp('hr_manager_action_at')->nullable();
            $table->text('comments_by_hr_manager')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employee_hiring_requests', function (Blueprint $table) {
            $table->bigInteger('reporting_to')->unsigned()->index()->nullable();
            $table->foreign('reporting_to')->references('id')->on('users')->onDelete('cascade');
        });
        Schema::table('job_descriptions', function (Blueprint $table) {
            $table->bigInteger('job_title')->unsigned()->index()->nullable();
            $table->foreign('job_title')->references('id')->on('master_job_positions')->onDelete('cascade');
            $table->bigInteger('reporting_to')->unsigned()->index()->nullable();
            $table->foreign('reporting_to')->references('id')->on('users')->onDelete('cascade');
            $table->bigInteger('department_id')->unsigned()->index()->nullable();
            $table->foreign('department_id')->references('id')->on('master_departments')->onDelete('cascade');
        });
        Schema::table('interview_summary_reports', function (Blueprint $table) {
            $table->enum('action_by_department_head', ['pending', 'approved','rejected'])->nullable();
            $table->bigInteger('department_head_id')->unsigned()->index()->nullable();
            $table->foreign('department_head_id')->references('id')->on('users')->onDelete('cascade');
            $table->timestamp('department_head_action_at')->nullable();
            $table->text('comments_by_department_head')->nullable();
            $table->dropColumn('action_by_hr_manager');
            $table->dropForeign(['hr_manager_id']);
            $table->dropColumn('hr_manager_id');
            $table->dropColumn('hr_manager_action_at');
            $table->dropColumn('comments_by_hr_manager');
        });
    }
};
