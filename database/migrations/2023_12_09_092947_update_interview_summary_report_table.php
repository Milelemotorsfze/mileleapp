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
            $table->decimal('candidate_expected_salary', 10,2)->default('0.00');
            $table->datetime('offer_letter_send_at')->nullable();
            $table->datetime('offer_letter_verified_at')->nullable();
            $table->bigInteger('offer_letter_verified_by')->unsigned()->index()->nullable()->after('offer_letter_verified_at');
            $table->foreign('offer_letter_verified_by')->references('id')->on('users')->onDelete('cascade');
            $table->decimal('total_salary', 10,2)->default('0.00');
            $table->string('email')->nullable();
        });
        Schema::table('emp_profile', function (Blueprint $table) {
            $table->enum('type',['employee','candidate'])->default('employee')->nullable()->after('id');
            $table->bigInteger('interview_summary_id')->unsigned()->index()->nullable()->after('user_id');
            $table->foreign('interview_summary_id')->references('id')->on('interview_summary_reports')->onDelete('cascade');
            $table->datetime('personal_information_send_at')->nullable()->after('personal_information_created_at');
            $table->datetime('personal_information_verified_at')->nullable()->after('personal_information_send_at');
            $table->bigInteger('personal_information_verified_by')->unsigned()->index()->nullable()->after('personal_information_verified_at');
            $table->foreign('personal_information_verified_by')->references('id')->on('users')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('interview_summary_reports', function (Blueprint $table) {
            $table->dropColumn('candidate_expected_salary');
            $table->dropColumn('offer_letter_send_at');
            $table->dropColumn('offer_letter_verified_at');
            $table->dropForeign(['offer_letter_verified_by']);
            $table->dropColumn('offer_letter_verified_by');
            $table->dropColumn('total_salary');
            $table->dropColumn('email');
        });
        Schema::table('emp_profile', function (Blueprint $table) {
            $table->dropColumn('type');
            $table->dropForeign(['interview_summary_id']);
            $table->dropColumn('interview_summary_id');
            $table->dropColumn('personal_information_send_at');
            $table->dropColumn('personal_information_verified_at');
            $table->dropForeign(['personal_information_verified_by']);
            $table->dropColumn('personal_information_verified_by');
        });
    }
};
