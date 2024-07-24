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
            $table->string('status')->nullable(); // Adding a new nullable column
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vehicles_supplier_account_transaction', function (Blueprint $table) {
            $table->dropColumn('status'); // Dropping the new column
        });
    }
};
