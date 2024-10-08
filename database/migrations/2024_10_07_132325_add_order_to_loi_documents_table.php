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
        Schema::table('letter_of_indent_documents', function (Blueprint $table) {
            $table->integer('order')->nullable()->after('is_passport');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('letter_of_indent_documents', function (Blueprint $table) {
            $table->dropColumn('order')->nullable()->after('is_passport');
        });
    }
};
