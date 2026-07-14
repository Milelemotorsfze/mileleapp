<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('purchasing_order', function (Blueprint $table) {
            $table->boolean('reservation_created_mail_pending')->default(false)->after('sales_person_id');
        });
    }

    public function down(): void
    {
        Schema::table('purchasing_order', function (Blueprint $table) {
            $table->dropColumn('reservation_created_mail_pending');
        });
    }
};
