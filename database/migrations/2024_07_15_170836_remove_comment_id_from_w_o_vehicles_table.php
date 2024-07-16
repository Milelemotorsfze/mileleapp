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
            $table->dropForeign(['comment_id']); // Drops the foreign key constraint
            $table->dropColumn('comment_id'); // Drops the column
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('w_o_vehicles', function (Blueprint $table) {
            $table->unsignedBigInteger('comment_id')->nullable(); 
            $table->foreign('comment_id')->references('id')->on('w_o_comments')->onDelete('set null');
        });
    }
};
