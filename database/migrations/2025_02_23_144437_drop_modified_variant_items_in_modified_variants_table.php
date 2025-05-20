<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('modified_variants', function (Blueprint $table) {
            // Check if foreign key exists before dropping
            $foreignKeys = DB::select("SELECT CONSTRAINT_NAME FROM information_schema.KEY_COLUMN_USAGE 
                                       WHERE TABLE_NAME = 'modified_variants' 
                                       AND COLUMN_NAME = 'modified_variant_items' 
                                       AND CONSTRAINT_NAME != 'PRIMARY'");

            if (!empty($foreignKeys)) {
                $constraintName = $foreignKeys[0]->CONSTRAINT_NAME;
                Schema::table('modified_variants', function (Blueprint $table) use ($constraintName) {
                    $table->dropForeign([$constraintName]);
                });
            }

            // Drop the column
            if (Schema::hasColumn('modified_variants', 'modified_variant_items')) {
                $table->dropColumn('modified_variant_items');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('modified_variants', function (Blueprint $table) {
            $table->bigInteger('modified_variant_items')->unsigned()->index()->nullable();
            $table->foreign('modified_variant_items')->references('id')->on('variant_items');
        });
    }
};
