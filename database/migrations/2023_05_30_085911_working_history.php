<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('working_history', function (Blueprint $table) {
            $table->id();
            $table->string('company_name');
            $table->string('designation');
            $table->string('location');
            $table->date('todate');
            $table->date('fromdate');
            $table->bigInteger('emp_profile_id')->unsigned()->index()->nullable();
            $table->foreign('emp_profile_id')->references('id')->on('emp_profile')->onDelete('cascade');
            $table->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('working_history');
    }
};
