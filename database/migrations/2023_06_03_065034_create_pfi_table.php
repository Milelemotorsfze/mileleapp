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
        Schema::create('pfi', function (Blueprint $table) {
            $table->id();
            $table->string('pfi_reference_number')->nullable();
            $table->decimal('amount')->nullable();
            $table->longText('comment')->nullable();
            $table->string('status')->nullable();
            $table->string('pfi_document')->nullable();
            $table->date('pfi_date')->nullable();
            $table->bigInteger('letter_of_indent_id')->unsigned()->index()->nullable();
            $table->foreign('letter_of_indent_id')->references('id')->on('letter_of_indents');
            $table->bigInteger('created_by')->unsigned()->index()->nullable();
            $table->foreign('created_by')->references('id')->on('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pfi');
    }
};
