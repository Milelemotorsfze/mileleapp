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
        Schema::table('vehicle_variant_histories', function (Blueprint $table) {
             // Drop the old foreign key constraint
             $table->dropForeign(['varaints_new']);
            
             // Drop the old index
             $table->dropIndex(['varaints_new']);
             
             // Add the new foreign key constraint
             $table->foreign('varaints_new')->references('id')->on('varaints')->onDelete('cascade');
             
             // Recreate the index
             $table->index('varaints_new');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vehicle_variant_histories', function (Blueprint $table) {
            // Drop the new foreign key constraint
            $table->dropForeign(['varaints_new']);
            
            // Drop the new index
            $table->dropIndex(['varaints_new']);
            
            // Revert back to the old foreign key constraint
            $table->foreign('varaints_new')->references('id')->on('supplier_account_transaction')->onDelete('cascade');
            
            // Recreate the old index
            $table->index('varaints_new');
        });
    }
};
