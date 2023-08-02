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
        Schema::table('vehicles', function (Blueprint $table) {
            $table->text('pdi_remarks')->nullable();
            $table->text('grn_remark')->nullable();
            $table->date('pdi_date')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vehicles', function (Blueprint $table) {
            $table->dropColumn('pdi_remarks');
            $table->dropColumn('grn_remark');
            $table->dropColumn('pdi_date');
        });
    }
};
