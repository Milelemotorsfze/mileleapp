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
        Schema::table('w_o_vehicle_addons', function (Blueprint $table) {
            // Check if the foreign key exists before trying to drop it
            $foreignKeys = DB::select("SELECT CONSTRAINT_NAME FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE WHERE TABLE_NAME = 'w_o_vehicle_addons' AND COLUMN_NAME = 'comment_id' AND CONSTRAINT_NAME = 'w_o_vehicle_addons_comment_id_foreign'");
            if (count($foreignKeys) > 0) {
                $table->dropForeign(['comment_id']); // Drops the foreign key constraint
            }

            // Check if the column exists before trying to drop it
            if (Schema::hasColumn('w_o_vehicle_addons', 'comment_id')) {
                $table->dropColumn('comment_id'); // Drops the column
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('w_o_vehicle_addons', function (Blueprint $table) {
            if (!Schema::hasColumn('w_o_vehicle_addons', 'comment_id')) {
                $table->unsignedBigInteger('comment_id')->nullable(); 
                $table->foreign('comment_id')->references('id')->on('w_o_comments')->onDelete('set null');
            }
        });
    }
};
