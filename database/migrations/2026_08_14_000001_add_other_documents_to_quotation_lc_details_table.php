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
        Schema::table('quotation_lc_details', function (Blueprint $table) {
            $table->boolean('doc_others')->default(false)->after('doc_inspection_certificate');
            $table->text('doc_others_details')->nullable()->after('doc_others');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('quotation_lc_details', function (Blueprint $table) {
            $table->dropColumn(['doc_others', 'doc_others_details']);
        });
    }
};
