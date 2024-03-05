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
        Schema::table('vehicles', function (Blueprint $table) {
            $table->bigInteger('supplier_inventory_id')->unsigned()->index()->nullable()->after('model_id');
            $table->foreign('supplier_inventory_id')->references('id')->on('supplier_inventories');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vehicles', function (Blueprint $table) {
            $table->dropForeign(['supplier_inventory_id']);
            $table->dropColumn('supplier_inventory_id');
        });
    }
};
