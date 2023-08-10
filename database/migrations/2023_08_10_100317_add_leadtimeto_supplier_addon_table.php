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
        Schema::table('supplier_addons', function (Blueprint $table) {
            $table->string('lead_time_min')->nullable();
            $table->string('lead_time_max')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('supplier_addons', function (Blueprint $table) {
            $table->dropColumn('lead_time_min');
            $table->dropColumn('lead_time_max');
        });
    }
};
