<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ModelYearCalculationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('model_year_calculation_rules')->insert([
            [
                'id' => 1,
                'name' => 'Rule 1',
                'value' => 6,
                'updated_at' => Carbon::now(),
                'created_at' => Carbon::now(),
            ],
            [
                'id' => 2,
                'name' => 'Rule 2',
                'value' => 7,
                'updated_at' => Carbon::now(),
                'created_at' => Carbon::now(),
            ],
            [
                'id' => 3,
                'name' => 'Rule 3',
                'value' => 8,
                'updated_at' => Carbon::now(),
                'created_at' => Carbon::now(),
            ]
        ]);

        DB::table('model_year_calculation_categories')->insert([
            [
                'id' => 1,
                'name' => 'Hilux',
                'model_year_rule_id' => 1,
                'updated_at' => Carbon::now(),
                'created_at' => Carbon::now(),
            ],
            [
                'id' => 2,
                'name' => 'Fortuner',
                'model_year_rule_id' => 1,
                'updated_at' => Carbon::now(),
                'created_at' => Carbon::now(),
            ],
            [
                'id' => 3,
                'name' => 'Corolla',
                'model_year_rule_id' => 2,
                'updated_at' => Carbon::now(),
                'created_at' => Carbon::now(),
            ],
            [
                'id' => 4,
                'name' => 'Camry',
                'model_year_rule_id ' => 2,
                'updated_at' => Carbon::now(),
                'created_at' => Carbon::now(),
            ],
            [
                'id' => 5,
                'name' => 'LC70',
                'model_year_rule_id' => 2,
                'updated_at' => Carbon::now(),
                'created_at' => Carbon::now(),
            ],
            [
                'id' => 6,
                'name' => 'Coaster',
                'model_year_rule_id' => 2,
                'updated_at' => Carbon::now(),
                'created_at' => Carbon::now(),
            ],
            [
                'id' => 7,
                'name' => 'Hiace',
                'model_year_rule_id' => 2,
                'updated_at' => Carbon::now(),
                'created_at' => Carbon::now(),
            ],
            [
                'id' => 8,
                'name' => 'Rumion',
                'model_year_rule_id' => 2,
                'updated_at' => Carbon::now(),
                'created_at' => Carbon::now(),
            ],
            [
                'id' => 9,
                'name' => 'Starlet',
                'model_year_rule_id' => 2,
                'updated_at' => Carbon::now(),
                'created_at' => Carbon::now(),
            ],
            [
                'id' => 10,
                'name' => 'Prado',
                'model_year_rule_id' => 3,
                'updated_at' => Carbon::now(),
                'created_at' => Carbon::now(),
            ],
            [
                'id' => 11,
                'name' => 'LC300',
                'model_year_rule_id' => 3,
                'updated_at' => Carbon::now(),
                'created_at' => Carbon::now(),
            ],
            [
                'id' => 12,
                'name' => 'Rav4',
                'model_year_rule_id' => 3,
                'updated_at' => Carbon::now(),
                'created_at' => Carbon::now(),
            ],
            [
                'id' => 13,
                'name' => 'Belta',
                'model_year_rule_id' => 3,
                'updated_at' => Carbon::now(),
                'created_at' => Carbon::now(),
            ]
        ]);

    }
}
