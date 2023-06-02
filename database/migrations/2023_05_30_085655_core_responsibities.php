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
        Schema::create('core_responsibities', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->bigInteger('emp_profile_id')->unsigned()->index()->nullable();
            $table->foreign('emp_profile_id')->references('id')->on('emp_profile')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('core_responsibities');
    }
};
