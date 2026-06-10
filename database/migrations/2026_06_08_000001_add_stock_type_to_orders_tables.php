<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('so', function (Blueprint $table) {
            $table->string('stock_type')->nullable()->after('sales_type');
        });

        Schema::table('purchasing_order', function (Blueprint $table) {
            $table->string('stock_type')->nullable()->after('po_type');
        });

        Schema::table('work_orders', function (Blueprint $table) {
            $table->string('stock_type')->nullable()->after('type');
        });
    }

    public function down(): void
    {
        Schema::table('so', function (Blueprint $table) {
            $table->dropColumn('stock_type');
        });

        Schema::table('purchasing_order', function (Blueprint $table) {
            $table->dropColumn('stock_type');
        });

        Schema::table('work_orders', function (Blueprint $table) {
            $table->dropColumn('stock_type');
        });
    }
};
