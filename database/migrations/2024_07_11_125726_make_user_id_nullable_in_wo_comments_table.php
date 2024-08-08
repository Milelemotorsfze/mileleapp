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
        Schema::table('w_o_comments', function (Blueprint $table) {
              // Make the user_id column nullable
              $table->unsignedBigInteger('user_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('w_o_comments', function (Blueprint $table) {
           // Revert the user_id column to not nullable
           $table->unsignedBigInteger('user_id')->nullable(false)->change();
        });
    }
};
