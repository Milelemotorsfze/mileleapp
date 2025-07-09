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
        Schema::table('so', function (Blueprint $table) {
                DB::statement("
                ALTER TABLE so 
                MODIFY COLUMN status 
                ENUM('Pending', 'Approved','Rejected','Cancelled') 
                DEFAULT 'Pending'
            ");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('so', function (Blueprint $table) {
             DB::statement("
                ALTER TABLE so 
                MODIFY COLUMN status 
                ENUM('Pending', 'Approved', 'Rejected') 
                DEFAULT 'Pending'
            ");
        });
    }
};
