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
            $table->integer('utilized_quantity')->default(0)->after('signature');
            $table->dropColumn('prefered_location');
            $table->dropColumn('destination');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('letter_of_indents', function (Blueprint $table) {
            $table->dropColumn('utilized_quantity');
            $table->string('prefered_location')->nullable();
            $table->string('destination')->nullable();
        });
    }
};
