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
    Schema::table('vehicles_supplier_account_transaction', function (Blueprint $table) {
        $table->bigInteger('vpa_id')->unsigned()->index()->nullable();
        $table->foreign('vpa_id')->references('id')->on('vendor_payment_adjustments')->onDelete('cascade');
        $table->bigInteger('popa_id')->unsigned()->index()->nullable();
        $table->foreign('popa_id')->references('id')->on('purchased_order_paid_amounts')->onDelete('cascade');
    });
}

/**
 * Reverse the migrations.
 */
public function down(): void
{
    Schema::table('vehicles_supplier_account_transaction', function (Blueprint $table) {
        $table->dropForeign(['vpa_id']);
        $table->dropColumn('vpa_id');
        $table->dropForeign(['popa_id']);
        $table->dropColumn('popa_id');
    });
}
};
