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
        Schema::table('interview_summary_reports', function (Blueprint $table) {
            $table->string('resume_file_name')->nullable()->after('hiring_request_id');
            $table->dropForeign(['name_of_interviewer']);
            $table->dropColumn('name_of_interviewer');
            $table->dropColumn('date_of_interview');
        });
        Schema::create('interviewers', function (Blueprint $table) {
            $table->bigInteger('interview_summary_report_id')->unsigned()->index()->nullable();
            $table->foreign('interview_summary_report_id')->references('id')->on('interview_summary_reports')->onDelete('cascade');
            $table->bigInteger('interviewer_id')->unsigned()->index()->nullable();
            $table->foreign('interviewer_id')->references('id')->on('users')->onDelete('cascade');
            $table->enum('round', ['telephonic','first','second','third','forth','fifth'])->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('interview_summary_reports', function (Blueprint $table) {
            $table->dropColumn('resume_file_name');
            $table->bigInteger('name_of_interviewer')->unsigned()->index()->nullable();
            $table->foreign('name_of_interviewer')->references('id')->on('users')->onDelete('cascade');
            $table->date('date_of_interview')->nullable();
        });
        Schema::dropIfExists('interviewers');
    }
};
