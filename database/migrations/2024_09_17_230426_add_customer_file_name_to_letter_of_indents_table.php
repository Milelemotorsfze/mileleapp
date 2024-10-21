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
            $table->string('customer_passport_file_name')->nullable()->after('loi_document_file');
            $table->string('customer_trade_license_file_name')->nullable()->after('loi_document_file');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('letter_of_indent_documents', function (Blueprint $table) {
            $table->dropColumn('customer_passport_file_name');
            $table->dropColumn('customer_trade_license_file_name');
        });
    }
};
