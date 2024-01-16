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
            $table->dropColumn('trial_period_joining_date');
            $table->dropColumn('permanent_joining_date');
            $table->dropForeign(['permanent_joining_location_id']);
            $table->dropColumn('permanent_joining_location_id');
            $table->dropColumn('transfer_to_date');
            $table->dropForeign(['transfer_to_location_id']);
            $table->dropColumn('transfer_to_location_id');
            $table->dropColumn('leave_joining_date');
            $table->dropForeign(['location_id']);
            $table->dropColumn('location_id');
        });
        Schema::table('joining_reports', function (Blueprint $table) {
            $table->date('joining_date')->nullable();
            $table->enum('new_emp_joining_type', ['trial_period', 'permanent'])->nullable();
            $table->bigInteger('joining_location')->unsigned()->index()->nullable();
            $table->foreign('joining_location')->references('id')->on('master_office_locations')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('joining_reports', function (Blueprint $table) {
            $table->date('trial_period_joining_date')->nullable();
            $table->date('permanent_joining_date')->nullable();
            $table->bigInteger('permanent_joining_location_id')->unsigned()->index()->nullable();
            $table->foreign('permanent_joining_location_id')->references('id')->on('master_office_locations')->onDelete('cascade');
            $table->date('transfer_to_date')->nullable();
            $table->bigInteger('transfer_to_location_id')->unsigned()->index()->nullable();
            $table->foreign('transfer_to_location_id')->references('id')->on('master_office_locations')->onDelete('cascade');
            $table->date('leave_joining_date')->nullable();
            $table->bigInteger('location_id')->unsigned()->index()->nullable();
            $table->foreign('location_id')->references('id')->on('master_office_locations')->onDelete('cascade');
        });
        Schema::table('joining_reports', function (Blueprint $table) {
            $table->dropColumn('joining_date');
            $table->dropForeign(['joining_location']);
            $table->dropColumn('joining_location');
            $table->dropColumn('new_emp_joining_type');
        });
    }
};
