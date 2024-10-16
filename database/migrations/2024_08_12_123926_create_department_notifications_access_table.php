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
        Schema::create('dnaccess', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('master_departments_id')->unsigned()->index()->nullable();
            $table->foreign('master_departments_id')->references('id')->on('master_departments');
            $table->bigInteger('department_notifications_id')->unsigned()->index()->nullable();
            $table->foreign('department_notifications_id')->references('id')->on('department_notifications');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dnaccess');
    }
};
