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
        Schema::table('wo_boe_claims', function (Blueprint $table) {
            // Check if the column `wo_boe_id` does not exist
            if (!Schema::hasColumn('wo_boe_claims', 'wo_boe_id')) {
                $table->unsignedBigInteger('wo_boe_id');
                $table->foreign('wo_boe_id')
                    ->references('id')
                    ->on('wo_boe')
                    ->onDelete('cascade');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('wo_boe_claims', function (Blueprint $table) {
            // Check if the column `wo_boe_id` exists before dropping
            if (Schema::hasColumn('wo_boe_claims', 'wo_boe_id')) {
                // Drop the foreign key constraint
                $table->dropForeign(['wo_boe_id']);

                // Drop the column
                $table->dropColumn('wo_boe_id');
            }
        });
    }
};
