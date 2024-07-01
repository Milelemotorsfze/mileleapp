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
        Schema::create('purchasing_order_swift_copies', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('purchasing_order_id')->unsigned()->index()->nullable();
            $table->foreign('purchasing_order_id')->references('id')->on('purchasing_order');
            $table->bigInteger('uploaded_by')->unsigned()->index()->nullable();
            $table->foreign('uploaded_by')->references('id')->on('users');
            $table->integer('number_of_vehicles');
            $table->integer('batch_no');
            $table->string('file_path');
            $table->timestamps();
        });
    }
    
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchasing_order_swift_copies');
    }
};
