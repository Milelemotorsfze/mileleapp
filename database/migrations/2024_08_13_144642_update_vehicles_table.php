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
            $table->bigInteger('dn_id')->unsigned()->index()->nullable();
            $table->foreign('dn_id')->references('id')->on('vehicle_dn');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vehicles', function (Blueprint $table) {
            // Drop the foreign key constraint first
            $table->dropForeign(['dn_id']);

            // Then drop the column
            $table->dropColumn('dn_id');
        });
    }
};
