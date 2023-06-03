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
        Schema::create('emp_doc', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('emp_profile_id')->unsigned()->index()->nullable();
            $table->foreign('emp_profile_id')->references('id')->on('emp_profile')->onDelete('cascade');
            $table->string('document_name');
            $table->string('document_path');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('emp_doc');
    }
};
