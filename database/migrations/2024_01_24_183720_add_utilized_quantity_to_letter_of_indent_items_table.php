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
        Schema::table('letter_of_indent_items', function (Blueprint $table) {
            $table->integer('utilized_quantity')->nullable()->after('approved_quantity')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('letter_of_indent_items', function (Blueprint $table) {
            $table->dropColumn('utilized_quantity');

        });
    }
};
