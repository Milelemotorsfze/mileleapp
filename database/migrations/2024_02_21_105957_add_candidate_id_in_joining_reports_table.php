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
        Schema::table('joining_reports', function (Blueprint $table) {
            $table->bigInteger('candidate_id')->unsigned()->index()->nullable();
            $table->foreign('candidate_id')->references('id')->on('emp_profile');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('joining_reports', function (Blueprint $table) {
            $table->dropForeign(['candidate_id']);
            $table->dropColumn('candidate_id');
        });
    }
};
