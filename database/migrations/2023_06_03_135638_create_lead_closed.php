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
        Schema::create('lead_closed', function (Blueprint $table) {
            $table->id();
            $table->date('date')->nullable();
            $table->text('so_number')->nullable();
            $table->text('sales_notes')->nullable();
            $table->bigInteger('call_id')->unsigned()->index()->nullable();
            $table->foreign('call_id')->references('id')->on('calls')->onDelete('cascade');
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
        Schema::dropIfExists('lead_closed');
    }
};
