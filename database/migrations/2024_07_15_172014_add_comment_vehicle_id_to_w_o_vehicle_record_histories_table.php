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
        Schema::table('w_o_vehicle_record_histories', function (Blueprint $table) {
            $table->unsignedBigInteger('comment_vehicle_id')->nullable(); 
            $table->foreign('comment_vehicle_id')->references('id')->on('comment_vehicle_mapping')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('w_o_vehicle_record_histories', function (Blueprint $table) {
            $table->dropForeign(['comment_vehicle_id']); // Drops the foreign key constraint
            $table->dropColumn('comment_vehicle_id'); // Drops the column
        });
    }
};
