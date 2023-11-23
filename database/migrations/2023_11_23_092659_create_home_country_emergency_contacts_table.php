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
        Schema::create('home_country_emergency_contacts', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('employee_id')->unsigned()->index()->nullable();
            $table->foreign('employee_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('name')->nullable();
            $table->bigInteger('relation')->unsigned()->index()->nullable();
            $table->foreign('relation')->references('id')->on('master_person_relations')->onDelete('cascade');
            $table->string('contact_number')->nullable();
            $table->string('alternative_contact_number')->nullable();
            $table->string('email_address')->nullable();
            $table->string('home_country_address')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('home_country_emergency_contacts');
    }
};
