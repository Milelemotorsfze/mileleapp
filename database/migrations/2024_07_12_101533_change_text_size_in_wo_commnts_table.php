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
            $table->mediumText('text')->change(); // allows up to 16,777,215 characters
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('w_o_comments', function (Blueprint $table) {
            $table->text('text')->change();
        });
    }
};
