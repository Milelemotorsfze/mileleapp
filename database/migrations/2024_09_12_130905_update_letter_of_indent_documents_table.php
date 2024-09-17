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
            $table->boolean('is_passport')->default(0)->after('letter_of_indent_id');
            $table->boolean('is_trade_license')->default(0)->after('letter_of_indent_id');
           
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('letter_of_indent_documents', function (Blueprint $table) {
            $table->dropColumn('is_passport');
            $table->dropColumn('is_trade_license');
        });
    }
};
