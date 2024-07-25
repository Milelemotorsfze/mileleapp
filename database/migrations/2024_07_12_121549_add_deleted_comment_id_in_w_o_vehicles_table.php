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
            $table->unsignedBigInteger('deleted_comment_id')->nullable(); 
            $table->foreign('deleted_comment_id')->references('id')->on('w_o_comments')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('w_o_vehicles', function (Blueprint $table) {
            $table->dropForeign(['deleted_comment_id']);
            $table->dropColumn('deleted_comment_id');
        });
    }
};
