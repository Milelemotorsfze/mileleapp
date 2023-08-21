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
        Schema::table('kit_common_items', function (Blueprint $table) {
            $table->dropForeign(['item_id']);
        });
        
        Schema::table('kit_common_items', function (Blueprint $table) {
            $table->foreign('item_id')->references('id')->on('addon_descriptions')->onDelete('cascade');  
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kit_common_items', function (Blueprint $table) {
            $table->dropForeign(['item_id']);
        });
        
        Schema::table('kit_common_items', function (Blueprint $table) {
            $table->foreign('item_id')->references('id')->on('addon_details')->onDelete('cascade');  
        });
    }
};
