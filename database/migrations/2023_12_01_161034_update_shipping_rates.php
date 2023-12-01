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
    Schema::table('shipping_rates', function (Blueprint $table) {
        // Drop the foreign key constraint if it exists
        if (Schema::hasColumn('shipping_rates', 'vendors_id')) {
            $table->dropForeign(['vendors_id']);
            $table->dropIndex(['vendors_id']);
            $table->dropColumn('vendors_id');
        }

        // Add the new column
        $table->bigInteger('suppliers_id')->unsigned()->index()->nullable();
        $table->foreign('suppliers_id')->references('id')->on('suppliers')->onDelete('cascade');
    });
}

/**
 * Reverse the migrations.
 */
public function down(): void
{
    Schema::table('shipping_rates', function (Blueprint $table) {
        // Drop the foreign key constraint if it exists
        if (Schema::hasColumn('shipping_rates', 'suppliers_id')) {
            $table->dropForeign(['suppliers_id']);
            $table->dropIndex(['suppliers_id']);
            $table->dropColumn('suppliers_id');
        }

        // Add back the vendors_id column
        $table->bigInteger('vendors_id')->unsigned()->index()->nullable();
        $table->foreign('vendors_id')->references('id')->on('vendors')->onDelete('cascade');
    });
}
};
