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
        Schema::table('letter_of_indents', function (Blueprint $table) {
            $table->dropColumn('loi_document_file');
            $table->date('expired_date')->nullable()->after('is_expired');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('letter_of_indents', function (Blueprint $table) {
            $table->string('loi_document_file');
            $table->dropColumn('expired_date');
        });
    }
};
