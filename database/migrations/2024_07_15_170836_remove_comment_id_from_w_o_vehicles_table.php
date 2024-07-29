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
            // Get the foreign keys of the table
            $foreignKeys = DB::select("SELECT CONSTRAINT_NAME FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE WHERE TABLE_NAME = 'w_o_vehicles' AND COLUMN_NAME = 'comment_id' AND REFERENCED_TABLE_NAME = 'w_o_comments'");

            // Check if the foreign key exists before trying to drop it
            if (count($foreignKeys) > 0) {
                $table->dropForeign(['comment_id']); // Drops the foreign key constraint
            }

            // Check if the column exists before trying to drop it
            if (Schema::hasColumn('w_o_vehicles', 'comment_id')) {
                $table->dropColumn('comment_id'); // Drops the column
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('w_o_vehicles', function (Blueprint $table) {
            if (!Schema::hasColumn('w_o_vehicles', 'comment_id')) {
                $table->unsignedBigInteger('comment_id')->nullable(); 
                $table->foreign('comment_id')->references('id')->on('w_o_comments')->onDelete('set null');
            }
        });
    }
};
