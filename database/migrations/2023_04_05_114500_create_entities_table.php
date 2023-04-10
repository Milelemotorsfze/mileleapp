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
        Schema::create('entities', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('document_number');
            $table->string('category');
            $table->string('source');
            $table->string('type');
            $table->string('country');
            $table->string('license_file');
            $table->string('tax_certificate_file');
            $table->string('passport_file');
            $table->string('national_id_file');
            $table->string('status')->comments('PENDING','APPROVAL');
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
        Schema::dropIfExists('entities');
    }
};
