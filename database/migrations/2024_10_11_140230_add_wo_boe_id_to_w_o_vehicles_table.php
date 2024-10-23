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
        Schema::table('w_o_vehicles', function (Blueprint $table) {
            // Adding the wo_boe_id column and setting up the foreign key
            $table->unsignedBigInteger('wo_boe_id')->nullable();

            // Assuming wo_boe is the name of the referenced table
            $table->foreign('wo_boe_id')->references('id')->on('wo_boe')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('w_o_vehicles', function (Blueprint $table) {
            // Drop the foreign key and column
            $table->dropForeign(['wo_boe_id']);
            $table->dropColumn('wo_boe_id');
        });
    }
};
