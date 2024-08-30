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
        Schema::table('users', function (Blueprint $table) {
            // Adding a new column for is_sales_rep with default value 'No'
            $table->enum('is_sales_rep', ['Yes', 'No'])->default('No')->after('email');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Dropping the is_sales_rep column if we rollback the migration
            $table->dropColumn('is_sales_rep');
        });
    }
};
