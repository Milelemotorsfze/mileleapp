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
        Schema::table('suppliers', function (Blueprint $table)
        {
            $table->dropColumn('communication_channels');
            $table->boolean('is_communication_mobile')->nullable();
            $table->string('is_communication_email')->nullable();
            $table->string('is_communication_postal')->nullable();
            $table->string('is_communication_fax')->nullable();
            $table->string('is_communication_any')->nullable();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('suppliers', function (Blueprint $table)
        {
            $table->string('communication_channels');
            $table->dropColumn('is_communication_mobile');
            $table->dropColumn('is_communication_email');
            $table->dropColumn('is_communication_postal');
            $table->dropColumn('is_communication_fax');
            $table->dropColumn('is_communication_any');
        });
    }
};
