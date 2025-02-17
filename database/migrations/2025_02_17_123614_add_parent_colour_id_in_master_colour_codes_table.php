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
        Schema::table('color_codes', function (Blueprint $table) {
            $table->bigInteger('parent_colour_id')->unsigned()->index()->nullable()->after('parent');
            $table->foreign('parent_colour_id')->references('id')->on('parent_colours');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('color_codes', function (Blueprint $table) {
            $table->dropForeign(['parent_colour_id']);
            $table->dropColumn('parent_colour_id');
        });
    }
};
