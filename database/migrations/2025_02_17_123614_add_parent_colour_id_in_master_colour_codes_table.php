<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('color_codes', function (Blueprint $table) {
            $table->dropColumn('parent');
            $table->bigInteger('parent_colour_id')->unsigned()->nullable()->after('belong_to');
            $table->foreign('parent_colour_id')->references('id')->on('parent_colours')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('color_codes', function (Blueprint $table) {
            $table->dropForeign(['parent_colour_id']);
            $table->dropColumn('parent_colour_id');
        });
    }
};
