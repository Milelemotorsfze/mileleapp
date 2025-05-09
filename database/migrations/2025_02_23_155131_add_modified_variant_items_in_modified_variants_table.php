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
        Schema::table('modified_variants', function (Blueprint $table) {
            $table->bigInteger('modified_variant_items')->unsigned()->index()->nullable()->after('base_varaint_id');
            $table->foreign('modified_variant_items')->references('id')->on('model_specification');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('modified_variants', function (Blueprint $table) {
            $table->dropForeign(['modified_variant_items']);
            $table->dropColumn('modified_variant_items');
        });
    }
};
