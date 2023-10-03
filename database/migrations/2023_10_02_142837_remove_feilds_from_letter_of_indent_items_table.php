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
        Schema::table('letter_of_indent_items', function (Blueprint $table) {
            $table->dropColumn('model');
            $table->dropColumn('sfx');
            $table->dropColumn('variant_name');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('letter_of_indent_items', function (Blueprint $table) {
            $table->string('model')->nullable();
            $table->string('sfx')->nullable();
            $table->string('variant_name')->nullable();
        });
    }
};
