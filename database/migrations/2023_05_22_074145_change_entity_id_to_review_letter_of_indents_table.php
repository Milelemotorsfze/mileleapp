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
        Schema::table('letter_of_indents', function (Blueprint $table) {
            $table->dropForeign(['entity_id']);
            $table->dropIndex(['entity_id']);
            $table->dropColumn('entity_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('letter_of_indents', function (Blueprint $table) {
            $table->bigInteger('entity_id')->unsigned()->index()->nullable();
            $table->foreign('entity_id')->references('id')->on('entities');
        });
    }
};
