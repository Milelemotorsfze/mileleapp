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
        Schema::table('master_model_descriptions', function (Blueprint $table) {
            $table->string('grade_or_trim')->nullable()->after('steering');
            $table->string('special_edition')->nullable()->after('grade_or_trim');
            $table->string('other')->nullable()->after('window_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('master_model_descriptions', function (Blueprint $table) {
            $table->dropColumn(['grade_or_trim', 'special_edition', 'other']);
        });
    }
};
