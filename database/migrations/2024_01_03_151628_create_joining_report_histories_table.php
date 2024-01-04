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
        Schema::create('joining_report_histories', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('joining_report_id')->unsigned()->index()->nullable();
            $table->foreign('joining_report_id')->references('id')->on('joining_reports')->onDelete('cascade');
            $table->text('icon')->nullable();
            $table->string('message')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('joining_report_histories');
    }
};
