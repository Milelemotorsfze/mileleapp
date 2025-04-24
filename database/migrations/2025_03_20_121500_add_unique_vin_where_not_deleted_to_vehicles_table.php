<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("
            ALTER TABLE vehicles 
            ADD COLUMN vin_unique_not_deleted VARCHAR(255) 
            GENERATED ALWAYS AS (IF(deleted_at IS NULL, vin, NULL)) STORED
        ");

        DB::statement("
            CREATE UNIQUE INDEX unique_vin_not_deleted ON vehicles (vin_unique_not_deleted)
        ");
    }

    public function down(): void
    {
        DB::statement("DROP INDEX unique_vin_not_deleted ON vehicles");
        DB::statement("ALTER TABLE vehicles DROP COLUMN vin_unique_not_deleted");
    }
};
