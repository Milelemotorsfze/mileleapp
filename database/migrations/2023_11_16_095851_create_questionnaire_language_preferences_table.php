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
        Schema::create('questionnaire_language_preferences', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('questionnaire_id')->unsigned()->index()->nullable();
            $table->foreign('questionnaire_id')->references('id')->on('employee_hiring_questionnaires')->onDelete('cascade');
            $table->bigInteger('language_id')->unsigned()->index()->nullable();
            $table->foreign('language_id')->references('id')->on('languages')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('questionnaire_language_preferences');
    }
};
