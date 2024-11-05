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
        Schema::create('lead_documents', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('lead_id'); // Foreign key to leads table
            $table->string('document_name'); // Original file name
            $table->string('document_path'); // Path of the file in storage
            $table->string('document_type'); // Type of the document (image/pdf)
            $table->timestamps();
            $table->foreign('lead_id')->references('id')->on('calls')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leads_documents');
    }
};
