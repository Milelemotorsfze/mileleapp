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
        Schema::table('variant_request', function (Blueprint $table) {
            $table->dropColumn('model_detail');
            $table->dropColumn('seat');
            $table->dropColumn('detail');
            $table->dropColumn('name');
            $table->dropColumn('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('variant_request', function (Blueprint $table) {
            $table->string('model_detail')->nullable();
            $table->string('seat')->nullable();
            $table->string('detail')->nullable();
            $table->string('name')->nullable();
            $table->string('status')->nullable();
        });
    }
};
