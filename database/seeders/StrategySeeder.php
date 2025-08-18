<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StrategySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $strategies = [
            [
                'id' => 1,
                'name' => 'Default Strategy',
                'status' => 'active',
                'lead_source_id' => 1,
                'created_by' => 1,
            ],
            [
                'id' => 2,
                'name' => 'Website Strategy',
                'status' => 'active',
                'lead_source_id' => 2,
                'created_by' => 1,
            ],
            [
                'id' => 3,
                'name' => 'Phone Strategy',
                'status' => 'active',
                'lead_source_id' => 3,
                'created_by' => 1,
            ],
            [
                'id' => 4,
                'name' => 'Email Strategy',
                'status' => 'active',
                'lead_source_id' => 4,
                'created_by' => 1,
            ],
            [
                'id' => 5,
                'name' => 'Referral Strategy',
                'status' => 'active',
                'lead_source_id' => 5,
                'created_by' => 1,
            ],
        ];

        foreach ($strategies as $strategy) {
            DB::table('strategies')->insertOrIgnore($strategy);
        }
    }
}
