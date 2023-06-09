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
        Schema::table('lead_rejection', function (Blueprint $table) {
            $table->bigInteger('call_id')->unsigned()->index()->nullable();
            $table->foreign('call_id')->references('id')->on('calls')->onDelete('cascade');
            $table->bigInteger('created_by')->unsigned()->index()->nullable();
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');  
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lead_rejection', function (Blueprint $table) {
         
        });
    }
};
