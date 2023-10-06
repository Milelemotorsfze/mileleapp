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
        Schema::table('incident', function (Blueprint $table) {
            $table->string('part_po_number')->nullable();
            $table->string('vehicle_status')->nullable();
            $table->string('update_remarks')->nullable();
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('incident', function (Blueprint $table) {
            $table->dropColumn('part_po_number');
            $table->dropColumn('vehicle_status');
            $table->dropColumn('update_remarks');
        });
    }
};
