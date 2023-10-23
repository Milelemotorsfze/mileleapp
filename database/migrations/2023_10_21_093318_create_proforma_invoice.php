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
        Schema::create('proforma_invoice', function (Blueprint $table) {
            $table->id();
            $table->string('category')->nullable();
            $table->string('validity')->nullable();
            $table->string('final_destination')->nullable();
            $table->string('incoterm')->nullable();
            $table->string('place_of_delivery')->nullable();
            $table->string('system_code')->nullable();
            $table->string('payment_terms')->nullable();
            $table->string('rep_name')->nullable();
            $table->string('rep_no')->nullable();
            $table->string('cb_name')->nullable();
            $table->string('cb_no')->nullable();
            $table->date('payment_due')->nullable();
            $table->string('net_aed')->nullable();
            $table->string('net_usd')->nullable();
            $table->string('accept_name')->nullable();
            $table->string('accept_designtion')->nullable();
            $table->string('accept_contact')->nullable();
            $table->bigInteger('sale_person_id')->unsigned()->index()->nullable();
            $table->foreign('sale_person_id')->references('id')->on('users')->onDelete('cascade');
            $table->bigInteger('created_by')->unsigned()->index()->nullable();
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
            $table->bigInteger('calls_id')->unsigned()->index()->nullable();
            $table->foreign('calls_id')->references('id')->on('calls')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('proforma_invoice');
    }
};
