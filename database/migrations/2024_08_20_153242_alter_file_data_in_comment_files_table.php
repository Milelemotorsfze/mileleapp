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
        Schema::table('comment_files', function (Blueprint $table) {
            // Alter the file_data column to longText
            $table->longText('file_data')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('comment_files', function (Blueprint $table) {
            // Revert the file_data column back to text
            $table->text('file_data')->change();
        });
    }
};
