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
        Schema::create('purchased_order_replies', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('purchased_order_messages_id')->unsigned()->index()->nullable();
            $table->foreign('purchased_order_messages_id')->references('id')->on('purchased_order_messages')->onDelete('cascade');
            $table->bigInteger('user_id')->unsigned()->index()->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->text('reply');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchased_order_replies');
    }
};
