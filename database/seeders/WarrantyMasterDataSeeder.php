<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use DB;

class WarrantyMasterDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $policies = [
            ['1','3 STAR'],
            ['2','BRONZE'],
            ['3','SILVER'],
            ['4','GOLD EXECUTIVE']
            ];
        foreach ($policies as $key => $value):
        $policy[] = [
            'id'       => $value[0],
            'name' => $value[1]
        ];
        endforeach ;
        DB::table('master_warranty_policies')->insert($policy);

        $coverageParts = [
            ['1','Battery (for hybrid cars)'],
            ['2','Engine'],
            ['3','Transmission (Manual & Automatic)'],
            ['4','Differential'],
            ['5','Air Conditioning'],
            ['6','Drive Axle (Front & Rear)'],
            ['7','Turbocharger/Supercharger Unit (Factory fitted)'],
            ['8','Electrical'],
            ['9','Engine cooling system'],
            ['10','Air Conditioning (Heating components)'],
            ['11','Torque Converter'],
            ['12','Continuously Variable Transmission CTX'],
            ['13','Front Wheel Drive'],
            ['14','Rear Wheel Drive'],
            ['15','4 Wheel Drive Vehicle'],
            ['16','Propshaft'],
            ['17','Fuel Systems (Diesel and Petrol)'],
            ['18','Steering (Including PAS)'],
            ['19','Brakes'],
            ['20','Anti-locking Brake System'],
            ['21','Working Materials'],
            ['22','Casing']
            ];
        foreach ($coverageParts as $key => $value):
        $coveragePart[] = [
            'id'       => $value[0],
            'name' => $value[1]
        ];
        endforeach ;
        DB::table('master_warranty_coverage_parts')->insert($coveragePart);

        $policycoverageParts = [
            ['1','1'],
            ['2','2'],
            ['3','3'],
            ['1','2'],
            ['2','2'],
            ['3','2'],
            ['4','2'],
            ['6','2'],
            ['1','3'],
            ['2','3'],
            ['3','3'],
            ['4','3'],
            ['5','3'],
            ['6','3'],
            ['7','3'],
            ['8','3'],
            ['9','3'],
            ['10','3'],
            ['1','4'],
            ['2','4'],
            ['3','4'],
            ['4','4'],
            ['5','4'],
            ['6','4'],
            ['7','4'],
            ['8','4'],
            ['9','4'],
            ['10','4'],
            ['11','4'],
            ['12','4'],
            ['13','4'],
            ['14','4'],
            ['15','4'],
            ['16','4'],
            ['17','4'],
            ['18','4'],
            ['19','4'],
            ['20','4'],
            ['21','4'],
            ['22','4']
        ];
        foreach ($policycoverageParts as $key => $value):
        $policycoveragePart[] = [
            'policies_id'       => $value[1],
            'parts_id' => $value[0]
        ];
        endforeach ;
        DB::table('warranty_policies_coverage_parts')->insert($policycoveragePart);
    }
}
