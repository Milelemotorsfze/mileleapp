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
        Schema::table('incident', function (Blueprint $table) {
            $table->string('status')->change()->nullable();
            $table->string('responsivity')->nullable();
            $table->bigInteger('inspection_id')->unsigned()->index()->nullable();
            $table->foreign('inspection_id')->references('id')->on('inspection')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('incident', function (Blueprint $table) {
        $table->date('status')->nullable()->change();
        $table->dropColumn(['responsivity', 'inspection_id']);
        $table->dropForeign(['inspection_id']);
    });
    }
};
