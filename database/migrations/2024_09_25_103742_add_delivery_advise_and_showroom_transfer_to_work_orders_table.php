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
        Schema::table('work_orders', function (Blueprint $table) {
            $table->enum('delivery_advise', ['yes', 'no'])->default('no')->after('so_number');
            $table->enum('showroom_transfer', ['yes', 'no'])->default('no')->after('delivery_advise');    
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('work_orders', function (Blueprint $table) {
            $table->dropColumn('delivery_advise');
            $table->dropColumn('showroom_transfer');
        });
    }
};
