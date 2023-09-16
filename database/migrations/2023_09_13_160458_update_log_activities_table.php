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
        Schema::table('log_activities', function (Blueprint $table) {
            $table->string('mac_address')->nullable()->after('ip');
            $table->string('browser_name')->nullable()->after('ip');
            $table->enum('device_name',['mobile','desktop','tablet'])->nullable()->after('ip');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('log_activities', function (Blueprint $table) {
            $table->dropColumn('mac_address');
            $table->dropColumn('browser_name');
            $table->dropColumn('device_name');

        });
    }
};
