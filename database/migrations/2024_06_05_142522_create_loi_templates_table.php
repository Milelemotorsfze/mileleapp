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
        Schema::create('loi_templates', function (Blueprint $table) {
            $table->id();
            $table->enum('template_type',['individual','business','milele_cars','trans_cars'])->nullable();
            $table->bigInteger('letter_of_indent_id')->unsigned()->index()->nullable();
            $table->foreign('letter_of_indent_id')->references('id')->on('letter_of_indents');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loi_templates');
    }
};
