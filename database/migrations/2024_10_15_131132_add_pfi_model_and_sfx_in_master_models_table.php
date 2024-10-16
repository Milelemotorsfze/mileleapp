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
            $table->string('pfi_model')->nullable()->after('model');
            $table->string('pfi_sfx')->nullable()->after('sfx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('master_models', function (Blueprint $table) {
            $table->dropColumn('pfi_model');
            $table->dropColumn('pfi_sfx');
        });
    }
};
