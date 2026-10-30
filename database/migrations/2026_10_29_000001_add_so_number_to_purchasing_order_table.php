<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('purchasing_order', function (Blueprint $table) {
            $table->string('so_number', 100)->nullable()->after('stock_type');
        });
    }

    public function down(): void
    {
        Schema::table('purchasing_order', function (Blueprint $table) {
            $table->dropColumn('so_number');
        });
    }
};
