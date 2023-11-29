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
        Schema::table('quotation_sub_items', function (Blueprint $table) {
            $table->bigInteger('quotation_id')->unsigned()->index()->nullable()->after('id');
            $table->foreign('quotation_id')->references('id')->on('quotations');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('quotation_sub_items', function (Blueprint $table) {
            $table->dropForeign('quotation_id');
            $table->dropIndex('quotation_id');
            $table->dropColumn('quotation_id');
        });
    }
};
