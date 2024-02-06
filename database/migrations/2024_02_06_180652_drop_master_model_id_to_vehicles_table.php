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
        Schema::table('vehicles', function (Blueprint $table) {
            // $table->dropIndex(['master_model_id']);
            $table->dropForeign(['master_model_id']);
            // $table->dropColumn(['master_model_id']);
            $table->foreign('master_model_id')->references('id')->on('master_models');
            // $table->foreign('master_model_id')->references('id')->on('master_models');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vehicles', function (Blueprint $table) {
            //
        });
    }
};
