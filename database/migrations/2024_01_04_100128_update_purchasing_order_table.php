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
        Schema::table('purchasing_order', function (Blueprint $table) {
            $table->string('currency')->nullable();
            $table->string('shippingmethod')->nullable();
            $table->string('shippingcost')->nullable();
            $table->string('totalcost')->nullable();
            $table->string('pol')->nullable();
            $table->string('pod')->nullable();
            $table->string('fd')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('purchasing_order', function (Blueprint $table) {
            $table->dropColumn('currency');
            $table->dropColumn('shippingmethod');
            $table->dropColumn('shippingcost');
            $table->dropColumn('totalcost');
            $table->dropColumn('pol');
            $table->dropColumn('pod');
            $table->dropColumn('fd');
        });
    }
};
