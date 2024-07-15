<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LOIExpiryConditionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('loi_expiry_conditions')->truncate();

        DB::table('loi_expiry_conditions')->insert([

            [
                'id' => 1,
                'category_name' => 'Individual',
                'expiry_duration_year' => 1,
                'created_by' => 16
                
            ],
            [
                'id' => 2,
                'category_name' => 'Company',
                'expiry_duration_year' => 1,
                'created_by' => 16
            ],
            [
                'id' => 3,
                'category_name' => 'Government',
                'expiry_duration_year' => 2,
                'created_by' => 16
            ],
            
        ]);

    }
}
