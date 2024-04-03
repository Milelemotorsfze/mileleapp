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
        Schema::table('quotation_details', function (Blueprint $table) {
            $table->bigInteger('muitlple_agents_id')->unsigned()->index()->nullable();
            $table->foreign('muitlple_agents_id')->references('id')->on('muitlple_agents')->onDelete('cascade');
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('quotation_details', function (Blueprint $table) {
            $table->dropForeign(['muitlple_agents_id']);
            $table->dropColumn('muitlple_agents_id');
        });
    }
};
