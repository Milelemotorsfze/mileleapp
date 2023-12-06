<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MasterVendorSubCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('master_vendor_sub_categories')->insert([
            [
                'id' => 1,
                'name' => 'Bulk',
                'slug' => 'Bulk',
                'master_vendor_category_id' => 1,
                'updated_at' => Carbon::now(),
                'created_at' => Carbon::now(),
            ],
            [
                'id' => 2,
                'name' => 'Small Segment',
                'slug' => 'Small Segment',
                'master_vendor_category_id' => 1,
                'updated_at' => Carbon::now(),
                'created_at' => Carbon::now(),
            ],
            [
                'id' => 3,
                'name' => 'Spare Parts',
                'slug' => 'spare_parts',
                'master_vendor_category_id' => 2,
                'updated_at' => Carbon::now(),
                'created_at' => Carbon::now(),
            ],
            [
                'id' => 4,
                'name' => 'Accessories',
                'slug' => 'accessories',
                'master_vendor_category_id' => 2,
                'updated_at' => Carbon::now(),
                'created_at' => Carbon::now(),
            ],
            [
                'id' => 5,
                'name' => 'Warranty',
                'slug' => 'warranty',
                'master_vendor_category_id' => 2,
                'updated_at' => Carbon::now(),
                'created_at' => Carbon::now(),
            ],
            [
                'id' => 6,
                'name' => 'Freelancer',
                'slug' => 'freelancer',
                'master_vendor_category_id' => 2,
                'updated_at' => Carbon::now(),
                'created_at' => Carbon::now(),
            ],
            [
                'id' => 7,
                'name' => 'Garage',
                'slug' => 'garage',
                'master_vendor_category_id' => 2,
                'updated_at' => Carbon::now(),
                'created_at' => Carbon::now(),
            ],
            [
                'id' => 8,
                'name' => 'Shipping',
                'slug' => 'Shipping',
                'master_vendor_category_id' => 3,
                'updated_at' => Carbon::now(),
                'created_at' => Carbon::now(),
            ],
            [
                'id' => 9,
                'name' => 'Other',
                'slug' => 'Other',
                'master_vendor_category_id' => 4,
                'updated_at' => Carbon::now(),
                'created_at' => Carbon::now(),
            ],
            [
                'id' => 10,
                'name' => 'Demand Planning',
                'slug' => 'demand_planning',
                'master_vendor_category_id' => 4,
                'updated_at' => Carbon::now(),
                'created_at' => Carbon::now(),
            ]
        ]);
    }
}
