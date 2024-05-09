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
        Schema::table('loi_country_criterias', function (Blueprint $table) {
            $table->dropColumn('is_longer_lead_time');
            $table->dropColumn('is_inflate_qty');
            $table->dropColumn('min_qty_per_passport');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('loi_country_criterias', function (Blueprint $table) {
            $table->boolean('is_longer_lead_time');
            $table->boolean('is_inflate_qty');
            $table->integer('min_qty_per_passport');
        });
    }
};
