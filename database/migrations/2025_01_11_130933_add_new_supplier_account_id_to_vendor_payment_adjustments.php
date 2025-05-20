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
        Schema::table('vendor_payment_adjustments', function (Blueprint $table) {
            // Add the new column
            $table->unsignedBigInteger('new_supplier_account_id')->nullable()->after('type');

            // Add the foreign key constraint
            $table->foreign('new_supplier_account_id')
                ->references('id')
                ->on('supplier_account')
                ->onDelete('cascade'); // Adjust `onDelete` behavior as needed (e.g., `set null`)
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vendor_payment_adjustments', function (Blueprint $table) {
            // Drop the foreign key constraint first
            $table->dropForeign(['new_supplier_account_id']);

            // Then drop the column
            $table->dropColumn('new_supplier_account_id');
        });
    }
};
