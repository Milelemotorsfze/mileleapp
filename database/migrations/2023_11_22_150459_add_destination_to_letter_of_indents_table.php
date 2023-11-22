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
            $table->renameColumn('shipment_method', 'destination')->change();
            $table->string('prefered_location')->nullable()->after('dealers');
            $table->string('so_number')->nullable()->after('dealers');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('letter_of_indents', function (Blueprint $table) {
            $table->renameColumn('destination', 'shipment_method')->change();
            $table->dropColumn('prefered_location');
            $table->dropColumn('so_number');
        });
    }
};
