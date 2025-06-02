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
         Schema::table('sales_order_history_details', function (Blueprint $table) {
            $table->bigInteger('so_item_id')->unsigned()->index()->nullable()->after('sales_order_history_id');
            $table->foreign('so_item_id')->references('id')->on('soitems');
            $table->bigInteger('so_variant_id')->unsigned()->index()->nullable()->after('sales_order_history_id');
            $table->foreign('so_variant_id')->references('id')->on('so_variants');
            $table->dropColumn('model_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sales_order_history_details', function (Blueprint $table) {
            $table->dropForeign(['so_item_id']);
            $table->dropColumn('so_item_id');
            $table->dropForeign(['so_variant_id']);
            $table->dropColumn('so_variant_id');
            $table->string('model_type');
        });
    }
};
