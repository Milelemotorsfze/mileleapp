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
        Schema::table('booking', function (Blueprint $table) {
            $table->dropColumn('name');
            $table->date('date')->nullable();
            $table->date('booking_start_date')->nullable();
            $table->date('booking_end_date')->nullable();
            $table->bigInteger('vehicle_id')->unsigned()->index()->nullable();
            $table->foreign('vehicle_id')->references('id')->on('vehicles')->onDelete('cascade');
            $table->bigInteger('calls_id')->unsigned()->index()->nullable();
            $table->foreign('calls_id')->references('id')->on('calls')->onDelete('cascade');
            $table->bigInteger('created_by')->unsigned()->index()->nullable();
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('booking', function (Blueprint $table) {
            $table->dropForeign(['vehicle_id']);
            $table->dropForeign(['calls_id']);
            $table->dropForeign(['created_by']);
            $table->dropColumn('vehicle_id');
            $table->dropColumn('calls_id');
            $table->dropColumn('created_by');
            $table->dropColumn('booking_end_date');
            $table->dropColumn('booking_start_date');
            $table->dropColumn('date');
            $table->string('name');
        });
    }
};
