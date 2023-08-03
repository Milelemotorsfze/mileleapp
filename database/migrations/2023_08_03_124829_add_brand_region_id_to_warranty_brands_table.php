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
        Schema::table('warranty_brands', function (Blueprint $table) {
            $table->bigInteger('brand_region_id')->unsigned()->index()->nullable()->after('brand_id');
            $table->foreign('brand_region_id')->references('id')->on('brand_regions');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('warranty_brands', function (Blueprint $table) {
            $table->dropForeign(['brand_region_id']);
            $table->dropIndex(['brand_region_id']);
            $table->dropColumn('brand_region_id');
        });
    }
};
