<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdatePreOrdersItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pre_orders_items', function (Blueprint $table) {
            // Drop the specified columns
            $table->dropColumn([
                'modelyear',
                'master_model_lines_id',
                'ex_colour',
                'int_colour',
                'description'
            ]);

            // Add the new column for variant ID
            $table->unsignedBigInteger('variant_id')->nullable();

            // Optionally, add a foreign key constraint if you want to link it to the variants table
            $table->foreign('variant_id')->references('id')->on('varaints')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pre_orders_items', function (Blueprint $table) {
            // Add back the removed columns (with their original data types)
            $table->string('modelyear')->nullable();
            $table->unsignedBigInteger('master_model_lines_id')->nullable();
            $table->unsignedBigInteger('ex_colour')->nullable();
            $table->unsignedBigInteger('int_colour')->nullable();
            $table->text('description')->nullable();

            // Remove the variant_id column
            $table->dropForeign(['variant_id']);
            $table->dropColumn('variant_id');
        });
    }
}