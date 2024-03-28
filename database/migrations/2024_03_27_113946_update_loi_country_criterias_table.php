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
            $table->integer('is_only_company_allowed')->change();
            $table->integer('min_qty_for_company')->nullable()->after('is_only_company_allowed');
            $table->integer('max_qty_for_company')->nullable()->after('is_only_company_allowed');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('loi_country_criterias', function (Blueprint $table) {
            $table->boolean('is_only_company_allowed')->change();
            $table->dropColumn('min_qty_for_company');
            $table->dropColumn('max_qty_for_company');
        });
    }
};
