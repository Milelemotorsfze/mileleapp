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
        Schema::table('emp_profile', function (Blueprint $table) {
            $table->datetime('documents_form_send_at')->nullable()->after('documents_verified_by');
            $table->bigInteger('documents_form_send_by')->unsigned()->index()->nullable()->after('documents_form_send_at');
            $table->foreign('documents_form_send_by')->references('id')->on('users')->onDelete('cascade');
            $table->datetime('documents_form_submit_at')->nullable()->after('documents_form_send_by');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('emp_profile', function (Blueprint $table) {
            $table->dropColumn('documents_form_send_at');
            $table->dropForeign(['documents_form_send_by']);
            $table->dropColumn('documents_form_send_by');
            $table->dropColumn('documents_form_submit_at');
        });
    }
};
