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
        Schema::create('joining_report_leave_types', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('joining_reports_id')->unsigned()->index()->nullable();
            $table->foreign('joining_reports_id')->references('id')->on('joining_reports')->onDelete('cascade');
            $table->enum('type_of_leave', ['annual', 'sick','unpaid','maternity_or_peternity','others'])->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('joining_report_leave_types');
    }
};
