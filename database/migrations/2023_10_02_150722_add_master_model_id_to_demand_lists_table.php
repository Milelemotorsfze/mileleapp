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
        Schema::table('demand_lists', function (Blueprint $table) {
            $table->bigInteger('master_model_id')->unsigned()->index()->nullable()->after('id');
            $table->foreign('master_model_id')->references('id')->on('master_models');
            $table->dropColumn('model');
            $table->dropColumn('sfx');
            $table->dropColumn('variant_name');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('demand_lists', function (Blueprint $table) {
            $table->dropIndex('master_model_id');
            $table->dropForeign('master_model_id');
            $table->dropColumn('master_model_id');
            $table->string('model')->nullable();
            $table->string('sfx')->nullable();
            $table->string('variant_name')->nullable();
        });
    }
};
