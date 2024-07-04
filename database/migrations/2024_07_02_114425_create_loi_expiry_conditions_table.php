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
        Schema::create('loi_expiry_conditions', function (Blueprint $table) {
            $table->id();
            $table->enum('category_name',['Individual','Company','Government'])->nullable();
            $table->integer('expiry_duration_year');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loi_expiry_conditions');
    }
};
