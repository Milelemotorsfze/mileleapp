<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MasterVendorCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('master_vendor_categories')->insert([
            [
                'id' => 1,
                'name' => 'Vehicles',
                'updated_at' => Carbon::now(),
                'created_at' => Carbon::now(),
            ],
            [
                'id' => 2,
                'name' => 'Parts and Accessories',
                'updated_at' => Carbon::now(),
                'created_at' => Carbon::now(),
            ],
            [
                'id' => 3,
                'name' => 'Shipping',
                'updated_at' => Carbon::now(),
                'created_at' => Carbon::now(),
            ],
            [
                'id' => 4,
                'name' => 'Other',
                'updated_at' => Carbon::now(),
                'created_at' => Carbon::now(),
            ],
        ]);
    }
}
