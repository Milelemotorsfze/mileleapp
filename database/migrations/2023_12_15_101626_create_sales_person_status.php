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
        Schema::create('sales_person_status', function (Blueprint $table) {
            $table->id();
            $table->string('remarks')->nullable();
            $table->string('status')->nullable();
            $table->bigInteger('sale_person_id')->unsigned()->index()->nullable();
            $table->foreign('sale_person_id')->references('id')->on('users')->onDelete('cascade');
            $table->bigInteger('created_by')->unsigned()->index()->nullable();
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales_person_status');
    }
};
