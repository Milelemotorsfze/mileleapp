<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('auto_lead_assign')->default(0)->after('remember_token');
            $table->boolean('manual_lead_assign')->default(0)->after('auto_lead_assign');
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['auto_lead_assign', 'manual_lead_assign']);
        });
    }

};
