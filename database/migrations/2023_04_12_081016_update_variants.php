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
        Schema::table('varaints', function (Blueprint $table) {
        $table->dropColumn('sfx');
        $table->dropColumn('model');
        $table->bigInteger('master_models_id')->unsigned()->index()->nullable();
        $table->foreign('master_models_id')->references('id')->on('master_models')->onDelete('cascade');
        $table->string('model_line');
        $table->string('brand');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('varaints', function (Blueprint $table) {
            $table->dropColumn('master_models_id');
            $table->dropColumn('model_line');
            $table->dropColumn('brand');
        });
    }
};
