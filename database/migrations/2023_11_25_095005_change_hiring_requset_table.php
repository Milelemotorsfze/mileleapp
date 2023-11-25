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
            $table->bigInteger('closed_by')->unsigned()->index()->nullable();
            $table->foreign('closed_by')->references('id')->on('users')->onDelete('cascade');
            $table->timestamp('closed_at')->nullable();
            $table->string('closed_comment')->nullable();
            $table->bigInteger('on_hold_by')->unsigned()->index()->nullable();
            $table->foreign('on_hold_by')->references('id')->on('users')->onDelete('cascade');
            $table->timestamp('on_hold_at')->nullable();
            $table->string('on_hold_comment')->nullable();
            $table->bigInteger('cancelled_by')->unsigned()->index()->nullable();
            $table->foreign('cancelled_by')->references('id')->on('users')->onDelete('cascade');
            $table->timestamp('cancelled_at')->nullable();            
            $table->string('cancelled_comment')->nullable();
        });
        Schema::table('interview_summary_reports', function (Blueprint $table) {
            $table->enum('seleced_status', ['pending','selected','rejected'])->default('pending')->nullable();
            $table->bigInteger('selected_status_by')->unsigned()->index()->nullable();
            $table->foreign('selected_status_by')->references('id')->on('users')->onDelete('cascade');
            $table->timestamp('selected_status_at')->nullable();
            $table->string('selected_status_comment')->nullable();
            $table->bigInteger('selected_hiring_request_id')->unsigned()->index()->nullable();
            $table->foreign('selected_hiring_request_id')->references('id')->on('employee_hiring_requests')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employee_hiring_requests', function (Blueprint $table) {
            $table->dropColumn('closed_by');
            $table->dropForeign(['closed_by']);
            $table->dropColumn('closed_at');
            $table->dropColumn('closed_comment');
            $table->dropColumn('on_hold_by');
            $table->dropForeign(['on_hold_by']);
            $table->dropColumn('on_hold_at');
            $table->dropColumn('on_hold_comment');
            $table->dropColumn('cancelled_by');
            $table->dropForeign(['cancelled_by']);
            $table->dropColumn('cancelled_at');
            $table->dropColumn('cancelled_comment');
        });
        Schema::table('interview_summary_reports', function (Blueprint $table) {
            $table->dropColumn('seleced_status');
            $table->dropColumn('selected_status_by');
            $table->dropForeign(['selected_status_by']);
            $table->dropColumn('selected_status_at');
            $table->dropColumn('selected_status_comment');
            $table->dropColumn('selected_hiring_request_id');
            $table->dropForeign(['selected_hiring_request_id']);
        });
    }
};
