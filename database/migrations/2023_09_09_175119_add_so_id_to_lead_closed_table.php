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
        Schema::table('lead_closed', function (Blueprint $table) {
            $table->dropColumn('so_number');
            $table->bigInteger('so_id')->unsigned()->index()->nullable();
            $table->foreign('so_id')->references('id')->on('so')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lead_closed', function (Blueprint $table) {
            $table->dropForeign(['so_id']);
            $table->dropColumn('so_id');
            $table->string('so_number')->nullable();
        });
    }
};
