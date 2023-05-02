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
        Schema::table('supplier_inventories', function (Blueprint $table) {
            $table->foreign('exterior_color_code_id')->references('id')->on('color_codes');
            $table->bigInteger('exterior_color_code_id')->unsigned()->index()->nullable()->after('color_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('supplier_inventories', function (Blueprint $table) {
            $table->dropColumn('exterior_color_code_id');
        });
    }
};
