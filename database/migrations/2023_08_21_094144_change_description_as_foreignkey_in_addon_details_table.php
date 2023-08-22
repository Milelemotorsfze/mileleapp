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
        Schema::table('addon_details', function (Blueprint $table) {
            $table->bigInteger('description')->unsigned()->index()->nullable();
            $table->foreign('description')->references('id')->on('addon_descriptions')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('addon_details', function (Blueprint $table) {
            $table->dropForeign('description');
            $table->dropIndex('description');
            $table->dropColumn('description');
        });
    }
};
