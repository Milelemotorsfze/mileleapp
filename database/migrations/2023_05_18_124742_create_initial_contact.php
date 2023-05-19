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
        Schema::create('initial_contact', function (Blueprint $table) {
            $table->id();
            $table->time('time');
            $table->date('date');
            $table->string('method');
            $table->string('outcome');
            $table->text('sales_notes')->nullable();
            $table->bigInteger('lead_id')->unsigned()->index()->nullable();
            $table->foreign('lead_id')->references('id')->on('calls');
            $table->bigInteger('customer_id')->unsigned()->index()->nullable();
            $table->foreign('customer_id')->references('id')->on('temp_customer');
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
        Schema::dropIfExists('initial_contact');
    }
};
