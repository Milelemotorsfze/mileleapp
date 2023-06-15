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
            $table->dropColumn('LOI_reference');
            // $table->dropColumn('review');
            $table->dropColumn('value');
//            $table->dropForeign('entity_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {

    }
};
