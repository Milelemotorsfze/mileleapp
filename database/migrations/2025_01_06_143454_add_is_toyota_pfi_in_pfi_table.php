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
        Schema::table('pfi', function (Blueprint $table) {
            $table->boolean('is_toyota_pfi')->default(true)->nullable()->after('amount');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pfi', function (Blueprint $table) {
            $table->dropColumn('is_toyota_pfi');
        });
    }
};


