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
        Schema::table('quotation_details', function (Blueprint $table) {
            $table->bigInteger('delivery_country')->unsigned()->index()->nullable();
            $table->foreign('delivery_country')->references('id')->on('countries')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('quotation_details', function (Blueprint $table) {
            $table->dropForeign(['delivery_country']);
            $table->dropColumn('delivery_country');
        });
    }
};
