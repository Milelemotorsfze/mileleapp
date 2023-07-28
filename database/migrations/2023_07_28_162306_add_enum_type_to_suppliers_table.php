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
        Schema::table('supplier_types', function (Blueprint $table) {
            $table->enum('supplier_type', ['Bulk','Small Segment','accessories','freelancer','garage','spare_parts','warranty','demand_planning'])->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('supplier_types', function (Blueprint $table) {
            //
        });
    }
};
