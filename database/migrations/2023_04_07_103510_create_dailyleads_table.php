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
        Schema::create('dailyleads', function (Blueprint $table) {
            $table->id();
            $table->string('brand');
            $table->string('model_line');
            $table->string('steering');
            $table->string('transmission');
            $table->string('engine');
            $table->string('trim');
            $table->string('model_year')->nullable()->default(null)->comment('The year of the car model.');
            $table->string('drive');
            $table->string('exterior_colour');
            $table->string('interior_colour');
            $table->string('region');
            $table->string('destination');
            $table->date('deadline');
            $table->text('remarks')->nullable()->comment('The deadline for the task.');
            $table->string('status');
            $table->string('type');
            $table->bigInteger('calls_id')->unsigned()->index()->nullable();
            $table->foreign('calls_id')->references('id')->on('calls')->onDelete('cascade');
            $table->bigInteger('sales_person')->unsigned()->nullable();
            $table->foreign('sales_person')->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dailyleads');
    }
};
