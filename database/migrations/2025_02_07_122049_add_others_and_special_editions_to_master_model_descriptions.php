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
            $table->text('others')->nullable()->after('drive_train');
            $table->text('specialEditions')->nullable()->after('others');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('master_model_descriptions', function (Blueprint $table) {
            $table->dropColumn(['others', 'specialEditions']);
        });
    }
};
