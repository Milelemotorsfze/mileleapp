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
        Schema::table('vin_changing', function (Blueprint $table) {
        $table->bigInteger('movements_id')->unsigned()->index()->nullable();
        $table->foreign('movements_id')->references('id')->on('movements')->onDelete('cascade');
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vin_changing', function (Blueprint $table) {
        $table->dropForeign('movements_id');
            $table->dropIndex('movements_id');
            $table->dropColumn('movements_id');
        });
    }
};
