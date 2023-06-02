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
        Schema::table('sales_person_laugauges', function (Blueprint $table) {
        $table->string('percentage')->nullable();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sales_person_laugauges', function (Blueprint $table) {
        $table->dropColumn('percentage');
        });
    }
};
