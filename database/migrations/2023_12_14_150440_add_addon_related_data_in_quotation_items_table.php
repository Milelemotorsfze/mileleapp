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
        Schema::table('quotation_items', function (Blueprint $table) {
            $table->enum('addon_type',['P','SP','K'])->nullable();

            $table->bigInteger('brand_id')->unsigned()->index()->after('quotation_id')->nullable();
            $table->foreign('brand_id')->references('id')->on('brands');

            $table->bigInteger('model_line_id')->unsigned()->index()->after('quotation_id')->nullable();
            $table->foreign('model_line_id')->references('id')->on('master_model_lines');

            $table->bigInteger('model_description_id')->unsigned()->index()->after('quotation_id')->nullable();
            $table->foreign('model_description_id')->references('id')->on('master_model_descriptions');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('quotation_items', function (Blueprint $table) {
            $table->dropColumn('addon_type');

            $table->dropForeign('brand_id');
            $table->dropIndex('brand_id');
            $table->dropColumn('brand_id');

            $table->dropForeign('model_line_id');
            $table->dropIndex('model_line_id');
            $table->dropColumn('model_line_id');

            $table->dropForeign('model_description_id');
            $table->dropIndex('model_description_id');
            $table->dropColumn('model_description_id');

        });
    }
};
