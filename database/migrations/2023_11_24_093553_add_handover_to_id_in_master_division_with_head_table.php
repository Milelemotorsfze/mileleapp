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
        Schema::table('master_division_with_heads', function (Blueprint $table) {
            $table->bigInteger('approval_handover_to')->unsigned()->index()->nullable();
            $table->foreign('approval_handover_to')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('master_division_with_heads', function (Blueprint $table) {
            $table->dropColumn('approval_handover_to');
        });
    }
};
