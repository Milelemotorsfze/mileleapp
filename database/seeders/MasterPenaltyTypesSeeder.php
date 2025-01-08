<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Masters\PenaltyTypes;

class MasterPenaltyTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $penaltyTypes = [
            ['name' => 'Case Management Demand Notice', 'is_active' => true, 'created_by' => 16, 'updated_by' => NULL, 'deleted_by' => NULL],
            ['name' => 'Deposit Claim Receivable - SG', 'is_active' => true, 'created_by' => 16, 'updated_by' => NULL, 'deleted_by' => NULL],
            ['name' => 'Deposit Forfeiture Demand Notice', 'is_active' => true, 'created_by' => 16, 'updated_by' => NULL, 'deleted_by' => NULL],
            ['name' => 'Duty - Alternative Deposit', 'is_active' => true, 'created_by' => 16, 'updated_by' => NULL, 'deleted_by' => NULL],
            ['name' => 'Duty - NR', 'is_active' => true, 'created_by' => 16, 'updated_by' => NULL, 'deleted_by' => NULL],
            ['name' => 'Late Export Fine', 'is_active' => true, 'created_by' => 16, 'updated_by' => NULL, 'deleted_by' => NULL],
            ['name' => 'Late Submission', 'is_active' => true, 'created_by' => 16, 'updated_by' => NULL, 'deleted_by' => NULL],
            ['name' => 'NR Claim Receivable', 'is_active' => true, 'created_by' => 16, 'updated_by' => NULL, 'deleted_by' => NULL],
            ['name' => 'NR Forfeiture Demand Notice', 'is_active' => true, 'created_by' => 16, 'updated_by' => NULL, 'deleted_by' => NULL],
            ['name' => 'SG Deposit Forfeiture Demand Notice', 'is_active' => true, 'created_by' => 16, 'updated_by' => NULL, 'deleted_by' => NULL],
        ];

        foreach ($penaltyTypes as $type) {
            PenaltyTypes::updateOrCreate(['name' => $type['name']], $type);
        }
    }
}
