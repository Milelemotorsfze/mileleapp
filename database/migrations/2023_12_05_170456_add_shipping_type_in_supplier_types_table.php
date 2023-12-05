<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("ALTER TABLE `supplier_types` CHANGE `supplier_type` `supplier_type` ENUM('Bulk','Small Segment','accessories','freelancer','garage',
            'spare_parts','warranty','demand_planning','Other','Shipping') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL;");

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE `supplier_types` CHANGE `supplier_type` `supplier_type` ENUM('Bulk','Small Segment','accessories','freelancer','garage',
                'spare_parts','warranty','demand_planning','Other') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL;");
    }
};
