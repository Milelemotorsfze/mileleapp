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
            $table->string('steering')->nullable()->after('model_description');
            $table->string('engine')->nullable()->after('steering');
            $table->string('fuel_type')->nullable()->after('engine');
            $table->string('transmission')->nullable()->after('fuel_type');
            $table->string('window_type')->nullable()->after('transmission');
            $table->string('drive_train')->nullable()->after('window_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('master_model_descriptions', function (Blueprint $table) {
            $table->dropColumn(['steering', 'engine', 'fuel_type', 'transmission', 'window_type', 'drive_train']);
        });
    }
};
