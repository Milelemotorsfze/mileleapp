<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use DB;

class AddonTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $addons = [
            ['1','Rear Camera']
            ];
        foreach ($addons as $key => $value):
        $addon[] = [
            'id'       => $value[0],
            'name' => $value[1]
        ];
        endforeach ;
        DB::table('addons')->insert($addon);
    }
}
