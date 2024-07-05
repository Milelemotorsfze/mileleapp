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
        Schema::create('loi_allowed_or_restricted_model_lines', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('model_line_id')->unsigned()->index()->nullable();
            $table->foreign('model_line_id')->references('id')->on('master_model_lines');
            $table->bigInteger('country_id')->unsigned()->index()->nullable();
            $table->foreign('country_id')->references('id')->on('countries');
            $table->boolean('is_restricted')->default(0);
            $table->boolean('is_allowed')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loi_allowed_or_restricted_model_lines');
    }
};
