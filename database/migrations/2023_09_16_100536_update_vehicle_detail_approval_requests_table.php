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
        Schema::table('vehicle_detail_approval_requests', function (Blueprint $table) {
        $table->date('action_at')->nullable();
        $table->string('remarks')->nullable();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vehicle_detail_approval_requests', function (Blueprint $table) {
        $table->dropColumn('action_at');
        $table->dropColumn('remarks');
        });
    }
};
