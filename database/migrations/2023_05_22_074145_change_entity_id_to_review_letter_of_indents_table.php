<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
public function up()
    {
        // Drop the foreign key constraint
        Schema::table('letter_of_indents', function (Blueprint $table) {
            $table->dropForeign('letter_of_indents_entity_id_foreign');
        });

        // Modify the column type
        Schema::table('letter_of_indents', function (Blueprint $table) {
            $table->string('entity_id')->nullable()->change();
        });

        // Add the foreign key constraint back
        Schema::table('letter_of_indents', function (Blueprint $table) {
            $table->string('entity_id')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Drop the foreign key constraint
        Schema::table('letter_of_indents', function (Blueprint $table) {
            $table->dropForeign('letter_of_indents_entity_id_foreign');
        });

        // Modify the column type back to its original state
        Schema::table('letter_of_indents', function (Blueprint $table) {
            $table->string('entity_id')->change();
        });

        // Add the foreign key constraint back
        Schema::table('letter_of_indents', function (Blueprint $table) {
            $table->bigInteger('entity_id')->change();
        });
    }
};
