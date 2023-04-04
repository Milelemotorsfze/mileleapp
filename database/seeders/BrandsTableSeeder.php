<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use DB;

class BrandsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $brands = [
            ['1','BENTLEY'],
            ['2','BMW'],
            ['3','CADILAC'],
            ['4','CHEVROLET'],
            ['5','FERRARI'],
            ['6','FORD'],
            ['7','GMC'],
            ['8','HINO'],
            ['9','HYUNDAI'],
            ['10','ISUZU'],
            ['11','KIA'],
            ['12','LAMBORGHINI'],
            ['13','LAND ROVER'],
            ['14','LEXUS'],
            ['15','MERCEDES BENZ'],
            ['16','MITSUBISHI'],
            ['17','NISSAN'],
            ['18','PEUGEOT'],
            ['19','PORSCHE'],
            ['20','ROLLS ROYCE'],
            ['21','SUZUKI'],
            ['22','TESLA'],
            ['23','TOYOTA'],
            ['24','VOLKSWAGEN']
            ];
        foreach ($brands as $key => $value):
        $brand[] = [
            'id'       => $value[0],
            'brand_name' => $value[1]
        ];
        endforeach ;
        DB::table('brands')->insert($brand);
    }
}
