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
      DB::statement("ALTER TABLE loi_templates MODIFY COLUMN template_type ENUM('individual','business','milele_cars','trans_cars','general') ");

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE loi_templates MODIFY COLUMN template_type ENUM('individual','business','milele_cars','trans_cars') ");
    }
};
