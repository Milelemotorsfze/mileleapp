<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class HRMMastersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void {
        DB::table('master_experience_levels')->insert([
            ['id' => 1,'name' => 'Entry Level','number_of_year_of_experience' => '0-1 Years','created_by' => 16,'created_at' => Carbon::now()->format('Y-m-d H:i:s'),],
            ['id' => 2,'name' => 'Mid-Level','number_of_year_of_experience' => '2-4 Years','created_by' => 16,'created_at' => Carbon::now()->format('Y-m-d H:i:s'),],
            ['id' => 3,'name' => 'Senior Level','number_of_year_of_experience' => '4-6 Years','created_by' => 16,'created_at' => Carbon::now()->format('Y-m-d H:i:s'),],
            ['id' => 4,'name' => 'Executive Level','number_of_year_of_experience' => '6+ Years','created_by' => 16,'created_at' => Carbon::now()->format('Y-m-d H:i:s'),],
            ['id' => 5,'name' => 'Managerial Level','number_of_year_of_experience' => '8+ Years','created_by' => 16,'created_at' => Carbon::now()->format('Y-m-d H:i:s'),],
            ['id' => 6,'name' => 'C-Suite Level','number_of_year_of_experience' => '10+ Years','created_by' => 16,'created_at' => Carbon::now()->format('Y-m-d H:i:s'),],
        ]);
        DB::table('master_office_locations')->insert([
            ['id' => 1,'name' => 'Head Office - AF03','type' => 'office','address' => 'Office No : AF-03, Samari Retail Dubai, United Arab Emirates','contact_number' =>'+97143235991','whatsapp_number' => '+971504996459','status' => 'active','created_by' => 16,'created_at' => Carbon::now()->format('Y-m-d H:i:s'),],
            ['id' => 2,'name' => 'Head Office - AF07','type' => 'office','address' => 'Office No : AF-07, Samari Retail Dubai, United Arab Emirates','contact_number' =>'+97143235991','whatsapp_number' => '+971504996459','status' => 'active','created_by' => 16,'created_at' => Carbon::now()->format('Y-m-d H:i:s'),],
            ['id' => 3,'name' => 'Showroom 11','type' => 'show_room','address' => 'Showroom 11, Dubai Auto Zone Al Aweer, UAE, United Arab Emirates','contact_number' =>'+97143235991','whatsapp_number' => '+971504996459','status' => 'active','created_by' => 16,'created_at' => Carbon::now()->format('Y-m-d H:i:s'),],
            ['id' => 4,'name' => 'Showroom 93','type' => 'show_room','address' => 'Showroom 93, Al Aweer Auto Market, Ras Al Khor, United Arab Emirates','contact_number' =>'+97143235991','whatsapp_number' => '+971504996459','status' => 'active','created_by' => 16,'created_at' => Carbon::now()->format('Y-m-d H:i:s'),],
            ['id' => 5,'name' => 'Showroom 124','type' => 'show_room','address' => 'Showroom 124, Dubai Auto Zone Al Aweer, UAE, United Arab Emirates','contact_number' =>'+97143235991','whatsapp_number' => '+971504996459','status' => 'active','created_by' => 16,'created_at' => Carbon::now()->format('Y-m-d H:i:s'),],
            ['id' => 6,'name' => 'Showroom 178','type' => 'show_room','address' => 'Showroom 178, Dubai Auto Zone Al Aweer, UAE, United Arab Emirates','contact_number' =>'+97143235991','whatsapp_number' => '+971504996459','status' => 'active','created_by' => 16,'created_at' => Carbon::now()->format('Y-m-d H:i:s'),],     
            ['id' => 7,'name' => 'Showroom 191','type' => 'show_room','address' => 'Showroom 191, Dubai Auto Zone Al Aweer, UAE, United Arab Emirates','contact_number' =>'+97143235991','whatsapp_number' => '+971504996459','status' => 'active','created_by' => 16,'created_at' => Carbon::now()->format('Y-m-d H:i:s'),],
            ['id' => 8,'name' => 'Office 7 - Ducamz','type' => 'office','address' => 'Ducamz, UAE, United Arab Emirates','contact_number' =>'+97143235991','whatsapp_number' => '+971504996459','status' => 'active','created_by' => 16,'created_at' => Carbon::now()->format('Y-m-d H:i:s'),],
            ['id' => 9,'name' => 'Milele Yard','type' => 'yard','address' => 'Milele Yard, Ras Al Khor Industrial Area 3, UAE, United Arab Emirates','contact_number' =>'+97143235991','whatsapp_number' => '+971504996459','status' => 'active','created_by' => 16,'created_at' => Carbon::now()->format('Y-m-d H:i:s'),],
            ['id' => 10,'name' => 'Jabel Ali Yard','type' => 'yard','address' => 'Jebel Ali Yard, Dubai Logistics City, UAE, United Arab Emirates','contact_number' =>'+97143235991','whatsapp_number' => '+971504996459','status' => 'active','created_by' => 16,'created_at' => Carbon::now()->format('Y-m-d H:i:s'),],
        ]);
        DB::table('master_deparments')->insert([
            ['id' => 1,'name' => 'Accounting & Finance Department','status' => 'active','created_by' => 16,'created_at' => Carbon::now()->format('Y-m-d H:i:s'),],
            ['id' => 2,'name' => 'Admin Department','status' => 'active','created_by' => 16,'created_at' => Carbon::now()->format('Y-m-d H:i:s'),],
            ['id' => 3,'name' => 'Business Development','status' => 'active','created_by' => 16,'created_at' => Carbon::now()->format('Y-m-d H:i:s'),],
            ['id' => 4,'name' => 'Chauffeur and Limousine Services','status' => 'active','created_by' => 16,'created_at' => Carbon::now()->format('Y-m-d H:i:s'),],
            ['id' => 5,'name' => 'Corporate Support','status' => 'active','created_by' => 16,'created_at' => Carbon::now()->format('Y-m-d H:i:s'),],
            ['id' => 6,'name' => 'Demand Planning Department','status' => 'active','created_by' => 16,'created_at' => Carbon::now()->format('Y-m-d H:i:s'),],     
            ['id' => 7,'name' => 'ESAO (Enterprise Strategy Analysis Operations) Department','status' => 'active','created_by' => 16,'created_at' => Carbon::now()->format('Y-m-d H:i:s'),],
            ['id' => 8,'name' => 'Human Resources Department','status' => 'active','created_by' => 16,'created_at' => Carbon::now()->format('Y-m-d H:i:s'),],
            ['id' => 9,'name' => 'IT Department','status' => 'active','created_by' => 16,'created_at' => Carbon::now()->format('Y-m-d H:i:s'),],
            ['id' => 10,'name' => 'Logistics Department','status' => 'active','created_by' => 16,'created_at' => Carbon::now()->format('Y-m-d H:i:s'),],
            ['id' => 11,'name' => 'Marketing Department','status' => 'active','created_by' => 16,'created_at' => Carbon::now()->format('Y-m-d H:i:s'),],
            ['id' => 12,'name' => 'Procurement Department - Spare Parts Procurement','status' => 'active','created_by' => 16,'created_at' => Carbon::now()->format('Y-m-d H:i:s'),],
            ['id' => 13,'name' => 'QA/QC Department','status' => 'active','created_by' => 16,'created_at' => Carbon::now()->format('Y-m-d H:i:s'),],
            ['id' => 14,'name' => 'Research and Development Department','status' => 'active','created_by' => 16,'created_at' => Carbon::now()->format('Y-m-d H:i:s'),],
            ['id' => 15,'name' => 'Sales Department','status' => 'active','created_by' => 16,'created_at' => Carbon::now()->format('Y-m-d H:i:s'),],     
            ['id' => 16,'name' => 'Sales Support Department','status' => 'active','created_by' => 16,'created_at' => Carbon::now()->format('Y-m-d H:i:s'),],
            ['id' => 17,'name' => 'Vehicle Procurement Department','status' => 'active','created_by' => 16,'created_at' => Carbon::now()->format('Y-m-d H:i:s'),],
            ['id' => 18,'name' => 'Warehouse & Operations','status' => 'active','created_by' => 16,'created_at' => Carbon::now()->format('Y-m-d H:i:s'),],
        ]);
        DB::table('master_job_positions')->insert([
            ['id' => 1,'name' => 'Chairman','status' => 'active','created_by' => 16,'created_at' => Carbon::now()->format('Y-m-d H:i:s'),],
            ['id' => 2,'name' => 'CEO','status' => 'active','created_by' => 16,'created_at' => Carbon::now()->format('Y-m-d H:i:s'),],
            ['id' => 3,'name' => 'VP Sales','status' => 'active','created_by' => 16,'created_at' => Carbon::now()->format('Y-m-d H:i:s'),],
            ['id' => 4,'name' => 'Sales','status' => 'active','created_by' => 16,'created_at' => Carbon::now()->format('Y-m-d H:i:s'),],
            ['id' => 5,'name' => 'Cleaner','status' => 'active','created_by' => 16,'created_at' => Carbon::now()->format('Y-m-d H:i:s'),],
            ['id' => 6,'name' => 'COO','status' => 'active','created_by' => 16,'created_at' => Carbon::now()->format('Y-m-d H:i:s'),],     
            ['id' => 7,'name' => 'Business Development Executive - VS Division','status' => 'active','created_by' => 16,'created_at' => Carbon::now()->format('Y-m-d H:i:s'),],
            ['id' => 8,'name' => 'Business Development Executive- VS Division','status' => 'active','created_by' => 16,'created_at' => Carbon::now()->format('Y-m-d H:i:s'),],
            ['id' => 9,'name' => 'Chauffer Service & Rental Car Manager','status' => 'active','created_by' => 16,'created_at' => Carbon::now()->format('Y-m-d H:i:s'),],
            ['id' => 10,'name' => 'Business Analyst','status' => 'active','created_by' => 16,'created_at' => Carbon::now()->format('Y-m-d H:i:s'),],
            ['id' => 11,'name' => 'Business Operations Lead','status' => 'active','created_by' => 16,'created_at' => Carbon::now()->format('Y-m-d H:i:s'),],
            ['id' => 12,'name' => 'Executive Assistant','status' => 'active','created_by' => 16,'created_at' => Carbon::now()->format('Y-m-d H:i:s'),],
            ['id' => 13,'name' => 'Business Operations Lead','status' => 'active','created_by' => 16,'created_at' => Carbon::now()->format('Y-m-d H:i:s'),],
            ['id' => 14,'name' => 'HR Manager','status' => 'active','created_by' => 16,'created_at' => Carbon::now()->format('Y-m-d H:i:s'),],
            ['id' => 15,'name' => 'HR Assistant','status' => 'active','created_by' => 16,'created_at' => Carbon::now()->format('Y-m-d H:i:s'),],     
            ['id' => 16,'name' => 'Recruiter','status' => 'active','created_by' => 16,'created_at' => Carbon::now()->format('Y-m-d H:i:s'),],
            ['id' => 17,'name' => 'HR & Admin Assistant','status' => 'active','created_by' => 16,'created_at' => Carbon::now()->format('Y-m-d H:i:s'),],
            ['id' => 18,'name' => 'HR Generalist','status' => 'active','created_by' => 16,'created_at' => Carbon::now()->format('Y-m-d H:i:s'),],
            ['id' => 19,'name' => 'Admin Coordinator','status' => 'active','created_by' => 16,'created_at' => Carbon::now()->format('Y-m-d H:i:s'),],
            ['id' => 20,'name' => 'Admin Assistant','status' => 'active','created_by' => 16,'created_at' => Carbon::now()->format('Y-m-d H:i:s'),],
            ['id' => 21,'name' => 'Office Boy','status' => 'active','created_by' => 16,'created_at' => Carbon::now()->format('Y-m-d H:i:s'),],
            ['id' => 22,'name' => 'VP Corporate Global Expansion','status' => 'active','created_by' => 16,'created_at' => Carbon::now()->format('Y-m-d H:i:s'),],
            ['id' => 23,'name' => 'Legal & Admin Advisor','status' => 'active','created_by' => 16,'created_at' => Carbon::now()->format('Y-m-d H:i:s'),],
            ['id' => 24,'name' => 'Team Lead - IT','status' => 'active','created_by' => 16,'created_at' => Carbon::now()->format('Y-m-d H:i:s'),],     
            ['id' => 25,'name' => 'IT Support','status' => 'active','created_by' => 16,'created_at' => Carbon::now()->format('Y-m-d H:i:s'),],
            ['id' => 26,'name' => 'IT Executive','status' => 'active','created_by' => 16,'created_at' => Carbon::now()->format('Y-m-d H:i:s'),],
            ['id' => 27,'name' => 'Web Developer','status' => 'active','created_by' => 16,'created_at' => Carbon::now()->format('Y-m-d H:i:s'),],
            ['id' => 28,'name' => 'Demand Planning Coordinator','status' => 'active','created_by' => 16,'created_at' => Carbon::now()->format('Y-m-d H:i:s'),],
            ['id' => 29,'name' => 'Operations Executive','status' => 'active','created_by' => 16,'created_at' => Carbon::now()->format('Y-m-d H:i:s'),],
            ['id' => 30,'name' => 'Finance Manager','status' => 'active','created_by' => 16,'created_at' => Carbon::now()->format('Y-m-d H:i:s'),],
            ['id' => 31,'name' => 'Senior Accountant','status' => 'active','created_by' => 16,'created_at' => Carbon::now()->format('Y-m-d H:i:s'),],
            ['id' => 32,'name' => 'Accountant','status' => 'active','created_by' => 16,'created_at' => Carbon::now()->format('Y-m-d H:i:s'),],
            ['id' => 33,'name' => 'Financial Analyst','status' => 'active','created_by' => 16,'created_at' => Carbon::now()->format('Y-m-d H:i:s'),],     
            ['id' => 34,'name' => 'Sales Support Executive','status' => 'active','created_by' => 16,'created_at' => Carbon::now()->format('Y-m-d H:i:s'),],
            ['id' => 35,'name' => 'Sales Support Coordinator','status' => 'active','created_by' => 16,'created_at' => Carbon::now()->format('Y-m-d H:i:s'),],
            ['id' => 36,'name' => 'Sales Executive','status' => 'active','created_by' => 16,'created_at' => Carbon::now()->format('Y-m-d H:i:s'),],
            ['id' => 37,'name' => 'Team Lead - Marketing','status' => 'active','created_by' => 16,'created_at' => Carbon::now()->format('Y-m-d H:i:s'),],
            ['id' => 38,'name' => 'Marketing Analyst','status' => 'active','created_by' => 16,'created_at' => Carbon::now()->format('Y-m-d H:i:s'),],
            ['id' => 39,'name' => 'Video Editor','status' => 'active','created_by' => 16,'created_at' => Carbon::now()->format('Y-m-d H:i:s'),],
            ['id' => 40,'name' => 'Digital Marketing Specialist','status' => 'active','created_by' => 16,'created_at' => Carbon::now()->format('Y-m-d H:i:s'),],
            ['id' => 41,'name' => 'Photographer','status' => 'active','created_by' => 16,'created_at' => Carbon::now()->format('Y-m-d H:i:s'),],
            ['id' => 42,'name' => 'Graphic Designer','status' => 'active','created_by' => 16,'created_at' => Carbon::now()->format('Y-m-d H:i:s'),],     
            ['id' => 43,'name' => 'Videographer','status' => 'active','created_by' => 16,'created_at' => Carbon::now()->format('Y-m-d H:i:s'),],
            ['id' => 44,'name' => '3D Animator','status' => 'active','created_by' => 16,'created_at' => Carbon::now()->format('Y-m-d H:i:s'),],
            ['id' => 45,'name' => 'Brand Analyst','status' => 'active','created_by' => 16,'created_at' => Carbon::now()->format('Y-m-d H:i:s'),],
            ['id' => 46,'name' => 'Procurement – Team Lead','status' => 'active','created_by' => 16,'created_at' => Carbon::now()->format('Y-m-d H:i:s'),],
            ['id' => 47,'name' => 'Procurement Administrator','status' => 'active','created_by' => 16,'created_at' => Carbon::now()->format('Y-m-d H:i:s'),],
            ['id' => 48,'name' => 'Procurement Assistant','status' => 'active','created_by' => 16,'created_at' => Carbon::now()->format('Y-m-d H:i:s'),],
            ['id' => 49,'name' => 'QA/QC Supervisor','status' => 'active','created_by' => 16,'created_at' => Carbon::now()->format('Y-m-d H:i:s'),],
            ['id' => 50,'name' => 'Vehicle Inspection Assistant','status' => 'active','created_by' => 16,'created_at' => Carbon::now()->format('Y-m-d H:i:s'),],
            ['id' => 51,'name' => 'Vehicle Controller','status' => 'active','created_by' => 16,'created_at' => Carbon::now()->format('Y-m-d H:i:s'),],     
            ['id' => 52,'name' => 'Logistics Executive','status' => 'active','created_by' => 16,'created_at' => Carbon::now()->format('Y-m-d H:i:s'),],
            ['id' => 53,'name' => 'Logistics Coordinator','status' => 'active','created_by' => 16,'created_at' => Carbon::now()->format('Y-m-d H:i:s'),],
            ['id' => 54,'name' => 'Warehouse Supervisor','status' => 'active','created_by' => 16,'created_at' => Carbon::now()->format('Y-m-d H:i:s'),],
            ['id' => 55,'name' => 'Warehouse In-Charge','status' => 'active','created_by' => 16,'created_at' => Carbon::now()->format('Y-m-d H:i:s'),],
            ['id' => 56,'name' => 'Yard Assistant','status' => 'active','created_by' => 16,'created_at' => Carbon::now()->format('Y-m-d H:i:s'),],
            ['id' => 57,'name' => 'Logistics Assistant / Messenger','status' => 'active','created_by' => 16,'created_at' => Carbon::now()->format('Y-m-d H:i:s'),],     
            ['id' => 58,'name' => 'Driver In-Charge','status' => 'active','created_by' => 16,'created_at' => Carbon::now()->format('Y-m-d H:i:s'),],
            ['id' => 59,'name' => 'Yard Attendant','status' => 'active','created_by' => 16,'created_at' => Carbon::now()->format('Y-m-d H:i:s'),],
            ['id' => 60,'name' => 'Bike Messenger','status' => 'active','created_by' => 16,'created_at' => Carbon::now()->format('Y-m-d H:i:s'),],
        ]);
    }
}
