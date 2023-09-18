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
        Schema::table('master_models', function (Blueprint $table) {
            $table->bigInteger('variant_id')->unsigned()->index()->nullable()->after('id');
            $table->foreign('variant_id')->references('id')->on('varaints');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('master_models', function (Blueprint $table) {
           $table->dropIndex('variant_id');
            $table->dropForeign('variant_id');
            $table->dropColumn('variant_id');

        });
    }
};
