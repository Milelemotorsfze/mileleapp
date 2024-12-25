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
        Schema::table('boe_penalties', function (Blueprint $table) {
            // Drop index if it exists
            if ($this->indexExists('boe_penalties', 'boe_penalties_wo_vehicle_id_index')) {
                $table->dropIndex('boe_penalties_wo_vehicle_id_index');
            }

            // Drop foreign key and column if they exist
            if (Schema::hasColumn('boe_penalties', 'wo_vehicle_id')) {
                $foreignKeyName = $this->getForeignKeyName('boe_penalties', 'wo_vehicle_id');
                if ($foreignKeyName) {
                    $table->dropForeign($foreignKeyName);
                }
                $table->dropColumn('wo_vehicle_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('boe_penalties', function (Blueprint $table) {
            if (!Schema::hasColumn('boe_penalties', 'wo_vehicle_id')) {
                $table->unsignedBigInteger('wo_vehicle_id');
                $table->foreign('wo_vehicle_id')
                    ->references('id')
                    ->on('w_o_vehicles')
                    ->onDelete('cascade');
            }
        });
    }
     /**
     * Helper function to check if an index exists.
     */
    private function indexExists(string $table, string $indexName): bool
    {
        $indexes = DB::select("SHOW INDEX FROM {$table} WHERE Key_name = ?", [$indexName]);
        return count($indexes) > 0;
    }

    /**
     * Helper function to get the foreign key name for a column.
     */
    private function getForeignKeyName(string $table, string $column): ?string
    {
        $result = DB::select("
            SELECT CONSTRAINT_NAME
            FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE
            WHERE TABLE_NAME = ? AND COLUMN_NAME = ? AND REFERENCED_TABLE_NAME IS NOT NULL
        ", [$table, $column]);

        return $result[0]->CONSTRAINT_NAME ?? null;
    }
};
