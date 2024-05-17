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
        Schema::table('master_models', function (Blueprint $table) {
            $table->decimal('amount_uae')->default(0)->change();
            $table->decimal('amount_belgium')->default(0)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('master_models', function (Blueprint $table) {
            $table->string('amount_uae')->change();
            $table->string('amount_belgium')->change();
        });

    }
};
