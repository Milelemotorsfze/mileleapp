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
            $table->unsignedBigInteger('delete_cvm_id')->nullable(); 
            $table->foreign('delete_cvm_id')->references('id')->on('comment_vehicle_mapping')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('w_o_vehicle_addons', function (Blueprint $table) {
            $table->dropForeign(['delete_cvm_id']); // Drops the foreign key constraint
            $table->dropColumn('delete_cvm_id'); // Drops the column
        });
    }
};
