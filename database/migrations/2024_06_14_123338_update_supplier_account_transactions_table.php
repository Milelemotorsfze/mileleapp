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
        Schema::table('supplier_account_transaction', function (Blueprint $table) {
            $table->decimal('transaction_amount', 15, 1)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('supplier_account_transaction', function (Blueprint $table) {
            $table->dropColumn('transaction_amount');
        });
    }
};
