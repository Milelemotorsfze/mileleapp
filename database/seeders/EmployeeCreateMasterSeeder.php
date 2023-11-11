<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class EmployeeCreateMasterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('master_genders')->insert([
            ['id' => 1,'name' => 'Male','created_by' => 16,'created_at' => Carbon::now()->format('Y-m-d H:i:s'),],
            ['id' => 2,'name' => 'Female','created_by' => 16,'created_at' => Carbon::now()->format('Y-m-d H:i:s'),],
            ['id' => 3,'name' => 'Other','created_by' => 16,'created_at' => Carbon::now()->format('Y-m-d H:i:s'),],
           ]);
        DB::table('master_marital_statuses')->insert([
            ['id' => 1,'name' => 'Single','created_by' => 16,'created_at' => Carbon::now()->format('Y-m-d H:i:s'),],
            ['id' => 2,'name' => 'Married','created_by' => 16,'created_at' => Carbon::now()->format('Y-m-d H:i:s'),],
            ['id' => 3,'name' => 'Divorced','created_by' => 16,'created_at' => Carbon::now()->format('Y-m-d H:i:s'),],
            ['id' => 4,'name' => 'Widowed','created_by' => 16,'created_at' => Carbon::now()->format('Y-m-d H:i:s'),],
            ['id' => 5,'name' => 'Other','created_by' => 16,'created_at' => Carbon::now()->format('Y-m-d H:i:s'),],
           ]);
        DB::table('master_religions')->insert([
            ['id' => 1,'name' => 'Buddhist','created_by' => 16,'created_at' => Carbon::now()->format('Y-m-d H:i:s'),],
            ['id' => 2,'name' => 'Christian','created_by' => 16,'created_at' => Carbon::now()->format('Y-m-d H:i:s'),],
            ['id' => 3,'name' => 'Hindu','created_by' => 16,'created_at' => Carbon::now()->format('Y-m-d H:i:s'),],
            ['id' => 4,'name' => 'Muslim','created_by' => 16,'created_at' => Carbon::now()->format('Y-m-d H:i:s'),],
           ]);
        DB::table('master_person_relations')->insert([
            ['id' => 1,'name' => 'Father (Biological)','created_by' => 16,'created_at' => Carbon::now()->format('Y-m-d H:i:s'),],
            ['id' => 2,'name' => 'Mother (Biological)','created_by' => 16,'created_at' => Carbon::now()->format('Y-m-d H:i:s'),],
            ['id' => 3,'name' => 'Father (Step)','created_by' => 16,'created_at' => Carbon::now()->format('Y-m-d H:i:s'),],
            ['id' => 4,'name' => 'Mother (Step)','created_by' => 16,'created_at' => Carbon::now()->format('Y-m-d H:i:s'),],
            ['id' => 5,'name' => 'Grandfather (Biological)','created_by' => 16,'created_at' => Carbon::now()->format('Y-m-d H:i:s'),],
            ['id' => 6,'name' => 'Grandmother (Biological)','created_by' => 16,'created_at' => Carbon::now()->format('Y-m-d H:i:s'),],
            ['id' => 7,'name' => 'Grand Father (Step)','created_by' => 16,'created_at' => Carbon::now()->format('Y-m-d H:i:s'),],
            ['id' => 8,'name' => 'Grand Mother (Step)','created_by' => 16,'created_at' => Carbon::now()->format('Y-m-d H:i:s'),],
            ['id' => 9,'name' => 'Father-in-Law','created_by' => 16,'created_at' => Carbon::now()->format('Y-m-d H:i:s'),],
            ['id' => 10,'name' => 'Mother-in-Law','created_by' => 16,'created_at' => Carbon::now()->format('Y-m-d H:i:s'),],
            ['id' => 11,'name' => 'God Father','created_by' => 16,'created_at' => Carbon::now()->format('Y-m-d H:i:s'),],
            ['id' => 12,'name' => 'God Mother','created_by' => 16,'created_at' => Carbon::now()->format('Y-m-d H:i:s'),],
            ['id' => 13,'name' => 'Son','created_by' => 16,'created_at' => Carbon::now()->format('Y-m-d H:i:s'),],
            ['id' => 14,'name' => 'Daughter','created_by' => 16,'created_at' => Carbon::now()->format('Y-m-d H:i:s'),],
            ['id' => 15,'name' => 'Brother','created_by' => 16,'created_at' => Carbon::now()->format('Y-m-d H:i:s'),],
            ['id' => 16,'name' => 'Sister','created_by' => 16,'created_at' => Carbon::now()->format('Y-m-d H:i:s'),],
            ['id' => 17,'name' => 'Stepson','created_by' => 16,'created_at' => Carbon::now()->format('Y-m-d H:i:s'),],
            ['id' => 18,'name' => 'Stepdaughter','created_by' => 16,'created_at' => Carbon::now()->format('Y-m-d H:i:s'),],
            ['id' => 19,'name' => 'Grandson','created_by' => 16,'created_at' => Carbon::now()->format('Y-m-d H:i:s'),],
            ['id' => 20,'name' => 'Granddaughter','created_by' => 16,'created_at' => Carbon::now()->format('Y-m-d H:i:s'),],
            ['id' => 21,'name' => 'Son-in-Law','created_by' => 16,'created_at' => Carbon::now()->format('Y-m-d H:i:s'),],
            ['id' => 22,'name' => 'Daughter-in-Law','created_by' => 16,'created_at' => Carbon::now()->format('Y-m-d H:i:s'),],
            ['id' => 23,'name' => 'Uncle','created_by' => 16,'created_at' => Carbon::now()->format('Y-m-d H:i:s'),],
            ['id' => 24,'name' => 'Aunt','created_by' => 16,'created_at' => Carbon::now()->format('Y-m-d H:i:s'),],
            ['id' => 25,'name' => 'Step Uncle','created_by' => 16,'created_at' => Carbon::now()->format('Y-m-d H:i:s'),],
            ['id' => 26,'name' => 'Step Aunt','created_by' => 16,'created_at' => Carbon::now()->format('Y-m-d H:i:s'),],
            ['id' => 27,'name' => 'Cousin','created_by' => 16,'created_at' => Carbon::now()->format('Y-m-d H:i:s'),],
            ['id' => 28,'name' => 'Nephew','created_by' => 16,'created_at' => Carbon::now()->format('Y-m-d H:i:s'),],
            ['id' => 29,'name' => 'Niece','created_by' => 16,'created_at' => Carbon::now()->format('Y-m-d H:i:s'),],
            ['id' => 30,'name' => 'Brother Son','created_by' => 16,'created_at' => Carbon::now()->format('Y-m-d H:i:s'),],
            ['id' => 31,'name' => 'Sister Daughter','created_by' => 16,'created_at' => Carbon::now()->format('Y-m-d H:i:s'),],
            ['id' => 32,'name' => 'Son Nephew','created_by' => 16,'created_at' => Carbon::now()->format('Y-m-d H:i:s'),],
            ['id' => 33,'name' => 'Daughter Niece','created_by' => 16,'created_at' => Carbon::now()->format('Y-m-d H:i:s'),],
            ['id' => 34,'name' => 'Great Grandson Uncle Son','created_by' => 16,'created_at' => Carbon::now()->format('Y-m-d H:i:s'),],
            ['id' => 35,'name' => 'Great Granddaughter Aunt Daughter','created_by' => 16,'created_at' => Carbon::now()->format('Y-m-d H:i:s'),],
            ['id' => 36,'name' => 'Cousin Son','created_by' => 16,'created_at' => Carbon::now()->format('Y-m-d H:i:s'),],
            ['id' => 37,'name' => 'Husband','created_by' => 16,'created_at' => Carbon::now()->format('Y-m-d H:i:s'),],
            ['id' => 38,'name' => 'Wife','created_by' => 16,'created_at' => Carbon::now()->format('Y-m-d H:i:s'),],
            ['id' => 39,'name' => 'Friend','created_by' => 16,'created_at' => Carbon::now()->format('Y-m-d H:i:s'),],
           ]);
    }
}
