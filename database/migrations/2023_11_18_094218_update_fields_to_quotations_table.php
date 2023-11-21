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
        Schema::table('quotations', function (Blueprint $table) {
            $table->string('document_type')->after('currency')->nullable();
            $table->string('shipping_method')->after('currency')->nullable();
            $table->longText('remarks')->after('currency')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('quotations', function (Blueprint $table) {
            $table->dropColumn('document_type');
            $table->dropColumn('shipping_method');
            $table->dropColumn('remarks');

        });
    }
};
