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
            $table->bigInteger('old_reporting_manager')->unsigned()->index()->nullable();
            $table->foreign('old_reporting_manager')->references('id')->on('users');
            $table->bigInteger('new_reporting_manager')->unsigned()->index()->nullable();
            $table->foreign('new_reporting_manager')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('joining_reports', function (Blueprint $table) {
            $table->dropForeign(['old_reporting_manager']);
            $table->dropColumn('old_reporting_manager');
            $table->dropForeign(['new_reporting_manager']);
            $table->dropColumn('new_reporting_manager');
        });
    }
};
