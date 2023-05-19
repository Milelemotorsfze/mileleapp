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
        Schema::create('customer_demand', function (Blueprint $table) {
            $table->id();
            $table->string('brand_name')->nullable();
            $table->string('model_line')->nullable();
            $table->string('trim')->nullable();
            $table->string('budge')->nullable();
            $table->string('model_year')->nullable();
            $table->string('engine_size')->nullable();
            $table->string('drive')->nullable();
            $table->string('exterior_colour')->nullable();
            $table->string('interior_colour')->nullable();
            $table->string('region')->nullable();
            $table->string('destination')->nullable();
            $table->string('steering')->nullable();
            $table->string('transmission')->nullable();
            $table->text('remarks')->nullable();
            $table->bigInteger('need_analysis_id')->unsigned()->index()->nullable();
            $table->foreign('need_analysis_id')->references('id')->on('need_analysis');
            $table->bigInteger('created_by')->unsigned()->index()->nullable();
            $table->foreign('created_by')->references('id')->on('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customer_demand');
    }
};
