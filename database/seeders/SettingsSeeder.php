<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('settings')->insert([

            [
                'id' => 1,
                'name' => 'AED-EUR / EUR-AED Convertion rate',
                'key' => 'aed_to_euro_convertion_rate',
                'value' => '3.925',
                'created_at' => Carbon::now(),
            ],
            [
                'id' => 2,
                'name' => 'AED-USD / USD-AED Convertion rate',
                'key' => 'aed_to_usd_convertion_rate',
                'value' => '3.6725',
                'created_at' => Carbon::now(),
            ],
            [
                'id' => 3,
                'name' => 'USD-EUR / EUR-USD Convertion rate',
                'key' => 'usd_to_euro_convertion_rate',
                'value' => '1.06907',
                'created_at' => Carbon::now(),
            ],
        ]);


    }
}
