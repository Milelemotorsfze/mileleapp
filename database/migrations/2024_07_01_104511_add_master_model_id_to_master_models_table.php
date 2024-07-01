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
        Schema::table('master_models', function (Blueprint $table) {
            $table->bigInteger('master_model_line_id')->unsigned()->index()->nullable()->after('variant_id');
            $table->foreign('master_model_line_id')->references('id')->on('master_model_lines');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('master_models', function (Blueprint $table) {
            $table->dropForeign(['master_model_line_id']);
            $table->dropColumn('master_model_line_id');
        });
    }
};
