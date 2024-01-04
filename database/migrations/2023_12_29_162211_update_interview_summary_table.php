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
            $table->bigInteger('offer_letter_send_by')->unsigned()->index()->nullable();
            $table->foreign('offer_letter_send_by')->references('id')->on('users')->onDelete('cascade');
//            $table->bigInteger('offer_letter_verified_by')->unsigned()->index()->nullable();
//            $table->foreign('offer_letter_verified_by')->references('id')->on('users')->onDelete('cascade');
        });
        Schema::table('emp_profile', function (Blueprint $table) {
            $table->string('offer_letter_fileName')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('interview_summary_reports', function (Blueprint $table) {
            $table->dropIndex(['offer_letter_send_by']);
            $table->dropForeign(['offer_letter_send_by']);
            $table->dropColumn('offer_letter_send_by');
            $table->dropIndex(['offer_letter_verified_by']);
            $table->dropForeign(['offer_letter_verified_by']);
            $table->dropColumn('offer_letter_verified_by');
        });
        Schema::table('emp_profile', function (Blueprint $table) {
            $table->dropColumn('offer_letter_fileName');
        });
    }
};
