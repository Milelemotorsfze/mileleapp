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
        Schema::create('w_o_record_histories', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['Set', 'Change','Unset'])->nullable();
            $table->unsignedBigInteger('work_order_id')->nullable();
            $table->foreign('work_order_id')->references('id')->on('work_orders')->onDelete('cascade');
            $table->unsignedBigInteger('user_id');
            $table->string('field_name');
            $table->text('old_value')->nullable();
            $table->text('new_value')->nullable();
            $table->timestamp('changed_at');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
       
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('w_o_record_histories');
    }
};
