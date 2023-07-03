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
        Schema::table('vehicles', function (Blueprint $table) {
            $table->dropForeign('vehicles_conversion_id_foreign');
            $table->dropColumn('conversion_id'); 
            $table->string('conversion'); 
        });
    }
    public function down(): void
    {
        Schema::table('vehicles', function (Blueprint $table) {
            $table->foreign('conversion_id')
                ->references('id')
                ->on('conversions')
                ->onDelete('cascade');
                $table->dropColumn('conversion'); 
        });
    }
};
