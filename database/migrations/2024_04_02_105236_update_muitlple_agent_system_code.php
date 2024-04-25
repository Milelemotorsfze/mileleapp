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
        Schema::table('muitlple_agent_system_code', function (Blueprint $table) {
            $table->bigInteger('quotation_items_id')->unsigned()->index()->nullable();
            $table->foreign('quotation_items_id')->references('id')->on('quotation_items')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('muitlple_agent_system_code', function (Blueprint $table) {
            $table->dropForeign(['quotation_items_id']);
            $table->dropColumn('quotation_items_id');
        });
    }
};
