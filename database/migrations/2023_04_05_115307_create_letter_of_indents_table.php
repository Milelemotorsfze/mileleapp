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
        Schema::create('letter_of_indents', function (Blueprint $table) {
            $table->id();
            $table->string('LOI_reference');
            $table->string('category');
            $table->string('submission_status')->comments('PFI_CREATED','UNDER_REVIEW','NEW','PARTIAL_APPROVAL');
            $table->string('status'); 
            $table->string('value');  
            $table->string('date');
            $table->longText('review');
            $table->bigInteger('entity_id')->unsigned()->index()->nullable();
            $table->foreign('entity_id')->references('id')->on('entities')->onDelete('cascade');
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
        Schema::dropIfExists('letter_of_indents');
    }
};
