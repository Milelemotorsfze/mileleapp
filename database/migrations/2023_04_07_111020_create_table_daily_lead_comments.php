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
        Schema::create('daily_lead_comments', function (Blueprint $table) {
            $table->id();
            $table->text('comment')->nullable()->comment('The deadline for the task.');
            $table->bigInteger('daily_lead_id')->unsigned()->index()->nullable();
            $table->foreign('daily_lead_id')->references('id')->on('dailyleads')->onDelete('cascade');
            $table->bigInteger('user_id')->unsigned()->index()->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('daily_lead_comments');
    }
};
