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
        Schema::table('fellow_up', function (Blueprint $table) {
            $table->bigInteger('calls_id')->unsigned()->index()->nullable();
            $table->foreign('calls_id')->references('id')->on('calls')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('fellow_up', function (Blueprint $table) {
            $table->dropForeign(['calls_id']);
            $table->dropColumn('calls_id');
        });
    }
};
