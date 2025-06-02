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
        Schema::table('so_variants', function (Blueprint $table) {
            $table->bigInteger('quotation_item_id')->unsigned()->index()->nullable()->after('so_id');
            $table->foreign('quotation_item_id')->references('id')->on('quotation_items');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('so_variants', function (Blueprint $table) {
            $table->dropForeign(['quotation_item_id']);
            $table->dropColumn('quotation_item_id');
        });
    }
};
