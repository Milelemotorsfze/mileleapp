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
            $table->bigInteger('interior_color_code_id')->unsigned()->index()->nullable()->after('color_code');
            $table->foreign('interior_color_code_id')->references('id')->on('color_codes')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('supplier_inventories', function (Blueprint $table) {
            $table->dropIndex(['interior_color_code_id']);
            $table->dropForeign(['interior_color_code_id']);
            $table->dropColumn('interior_color_code_id');
        });
    }
};
