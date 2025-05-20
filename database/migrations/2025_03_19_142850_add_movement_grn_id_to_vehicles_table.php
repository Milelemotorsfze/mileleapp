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
            $table->bigInteger('movement_grn_id')->unsigned()->index()->nullable()->after('grn_id');
            $table->foreign('movement_grn_id')->references('id')->on('movement_grns');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vehicles', function (Blueprint $table) {
            $table->dropForeign(['movement_grn_id']);
            $table->dropIndex(['movement_grn_id']);
            $table->dropColumn('movement_grn_id');
        });
    }
};
