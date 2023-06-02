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
        Schema::table('supplier_addon_temps', function (Blueprint $table) {
            $table->string('addon_code')->nullable()->change();
            $table->string('currency')->nullable()->change();
            $table->string('purchase_price')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('supplier_addon_temps', function (Blueprint $table) {
            $table->string('addon_code')->nullable(false)->change();
            $table->string('currency')->nullable(false)->change();
            $table->string('purchase_price')->nullable(false)->change();
        });
    }
};
