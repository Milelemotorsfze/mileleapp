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
        Schema::create('fellow_up', function (Blueprint $table) {
            $table->id();
            $table->time('time');
            $table->date('date');
            $table->string('method');
            $table->string('outcome');
            $table->text('sales_notes')->nullable();
            $table->bigInteger('initial_id')->unsigned()->index()->nullable();
            $table->foreign('initial_id')->references('id')->on('initial_contact');
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
        Schema::dropIfExists('fellow_up');
    }
};
