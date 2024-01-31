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
        Schema::table('so', function (Blueprint $table) {
            $table->string('total')->nullable();
            $table->string('receiving')->nullable();
            $table->string('paidinso')->nullable();
            $table->string('paidinperforma')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('so', function (Blueprint $table) {
            $table->dropColumn('total');
            $table->dropColumn('receiving');
            $table->dropColumn('paidinso');
            $table->dropColumn('paidinperforma');
        });
    }
};
