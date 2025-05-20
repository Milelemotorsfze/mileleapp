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
        Schema::table('boe_penalties', function (Blueprint $table) {
            // Add the `wo_boe_id` column and foreign key if it doesn't exist
            if (!Schema::hasColumn('boe_penalties', 'wo_boe_id')) {
                $table->unsignedBigInteger('wo_boe_id')->after('id');
                $table->foreign('wo_boe_id')
                    ->references('id')
                    ->on('wo_boe')
                    ->onDelete('cascade');
            }
    
            // Add the `invoice_number` column if it doesn't exist
            if (!Schema::hasColumn('boe_penalties', 'invoice_number')) {
                $table->string('invoice_number')->nullable()->after('wo_boe_id');
            }
    
            // Drop the `total_penalty_amount` column if it exists
            if (Schema::hasColumn('boe_penalties', 'total_penalty_amount')) {
                $table->dropColumn('total_penalty_amount');
            }
        });
    }
    
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('boe_penalties', function (Blueprint $table) {
            // Drop the foreign key and `wo_boe_id` column if it exists
            if (Schema::hasColumn('boe_penalties', 'wo_boe_id')) {
                $table->dropForeign(['wo_boe_id']);
                $table->dropColumn('wo_boe_id');
            }
    
            // Drop the `invoice_number` column if it exists
            if (Schema::hasColumn('boe_penalties', 'invoice_number')) {
                $table->dropColumn('invoice_number');
            }
    
            // Add the `total_penalty_amount` column back if it doesn't exist
            if (!Schema::hasColumn('boe_penalties', 'total_penalty_amount')) {
                $table->decimal('total_penalty_amount', 10, 2)->nullable();
            }
        });
    }
};
