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
        Schema::table('soitems', function (Blueprint $table) {
            $table->bigInteger('so_variant_id')->unsigned()->index()->nullable()->after('so_id');
            $table->foreign('so_variant_id')->references('id')->on('so_variants');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('soitems', function (Blueprint $table) {
            $table->dropForeign(['so_variant_id']);
            $table->dropColumn('so_variant_id');
        });
    }
};
