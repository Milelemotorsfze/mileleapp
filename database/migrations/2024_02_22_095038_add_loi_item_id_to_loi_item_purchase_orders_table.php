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
        Schema::table('loi_item_purchase_orders', function (Blueprint $table) {
            $table->bigInteger('master_model_id')->unsigned()->index()->nullable()->after('approved_loi_id');
            $table->foreign('master_model_id')->references('id')->on('master_models');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('loi_item_purchase_orders', function (Blueprint $table) {
            $table->dropForeign(['master_model_id']);
            $table->dropColumn('master_model_id');
        });
    }
};
