<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BrandRegionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('brand_regions')->insert([

            [
                'id' => 1,
                'name' => 'American',
            ],
            [
                'id' => 2,
                'name' => 'American/European',
            ],
            [
                'id' => 3,
                'name' => 'Chinese',
            ],
            [
                'id' => 4,
                'name' => 'European',
            ],
            [
                'id' => 5,
                'name' => 'Japanese/Korean',
            ],
        ]);

    }
}
