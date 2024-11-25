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
        Schema::table('pfi_item_purchase_orders', function (Blueprint $table) {
            $table->bigInteger('pfi_id')->unsigned()->index()->nullable()->after('id');
            $table->foreign('pfi_id')->references('id')->on('pfi');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pfi_item_purchase_orders', function (Blueprint $table) {
            $table->dropForeign(['pfi_id']);    
            $table->dropColumn('pfi_id');
        });
    }
};
