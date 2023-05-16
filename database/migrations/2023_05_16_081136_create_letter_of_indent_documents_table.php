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
        Schema::create('letter_of_indent_documents', function (Blueprint $table) {
            $table->id();
            $table->string('loi_document_file')->nullable();
            $table->bigInteger('letter_of_indent_id')->unsigned()->index()->nullable();
            $table->foreign('letter_of_indent_id')->references('id')->on('letter_of_indents');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('letter_of_indent_documents');
    }
};
