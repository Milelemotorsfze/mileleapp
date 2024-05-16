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
        Schema::create('loi_so_numbers', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('letter_of_indent_id')->unsigned()->index()->nullable();
            $table->foreign('letter_of_indent_id')->references('id')->on('letter_of_indents');
            $table->string('so_number')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loi_so_numbers');
    }
};
