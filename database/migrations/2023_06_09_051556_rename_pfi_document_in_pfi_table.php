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
        Schema::table('pfi', function (Blueprint $table) {
            $table->dropColumn('pfi_document');
            $table->string('pfi_document_with_sign')->nullable()->after('pfi_date');
            $table->string('pfi_document_without_sign')->nullable()->after('pfi_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pfi', function (Blueprint $table) {
            $table->string('pfi_document');
            $table->dropColumn('pfi_document_without_sign');
            $table->dropColumn('pfi_document_with_sign');
        });
    }
};
