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
        Schema::create('employee_hiring_request_histories', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('hiring_request_id')->unsigned()->index()->nullable();
            $table->foreign('hiring_request_id')->references('id')->on('employee_hiring_requests')->onDelete('cascade');
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
        Schema::dropIfExists('employee_hiring_request_histories');
    }
};
