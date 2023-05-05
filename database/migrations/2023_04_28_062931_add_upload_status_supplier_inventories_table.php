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
        Schema::table('supplier_inventories', function (Blueprint $table) {
            $table->string('upload_status')->after('veh_status')->nullable()->comment('ACTIVE,INACTIVE');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('supplier_inventories', function (Blueprint $table) {
            $table->dropColumn('upload_status');
        });
    }
};
