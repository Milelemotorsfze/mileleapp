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
        Schema::table('vehicle_pictures', function (Blueprint $table) {
            $table->dropColumn('GDN_link');
            $table->dropColumn('GRN_link');
            $table->dropColumn('modification_link');
            $table->string('vehicle_picture_link')->nullable()->after('vehicle_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vehicle_pictures', function (Blueprint $table) {
            $table->string('GDN_link');
            $table->string('GRN_link');
            $table->string('modification_link');
            $table->dropColumn('vehicle_picture_link');
        });
    }
};
