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
            $table->bigInteger('model_id')->unsigned()->index()->nullable()->after('varaints_id');
            $table->foreign('model_id')->references('id')->on('master_models');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vehicles', function (Blueprint $table) {
            $table->dropIndex(['model_id']);
            $table->dropForeign(['model_id']);
            $table->dropColumn(['model_id']);
        });
    }
};
