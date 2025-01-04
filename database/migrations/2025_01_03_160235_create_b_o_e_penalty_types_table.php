<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('boe_penalty_type', function (Blueprint $table) {
            $table->id(); // Primary Key
            $table->unsignedBigInteger('boe_penalties_id'); // Foreign Key to boe_penalties
            $table->unsignedBigInteger('penalty_types_id'); // Foreign Key to penalty_types
            $table->timestamps();

            // Foreign Key Constraints
            $table->foreign('boe_penalties_id')
                ->references('id')
                ->on('boe_penalties')
                ->onDelete('cascade');

            $table->foreign('penalty_types_id')
                ->references('id')
                ->on('penalty_types')
                ->onDelete('cascade');

            // Unique constraint to prevent duplicates
            $table->unique(['boe_penalties_id', 'penalty_types_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('boe_penalty_type');
    }
};
