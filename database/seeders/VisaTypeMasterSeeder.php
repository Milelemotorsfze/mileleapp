<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class VisaTypeMasterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('master_visa_types')->insert([
            ['id' => 1,'name' => 'Visit Visa','status' => 'active','created_by' => 16,'created_at' => Carbon::now()->format('Y-m-d H:i:s'),],
            ['id' => 2,'name' => 'Employment Visa','status' => 'active','created_by' => 16,'created_at' => Carbon::now()->format('Y-m-d H:i:s'),],
            ['id' => 3,'name' => 'Golden Visa','status' => 'active','created_by' => 16,'created_at' => Carbon::now()->format('Y-m-d H:i:s'),],
            ['id' => 4,'name' => 'Sponsored by Family','status' => 'active','created_by' => 16,'created_at' => Carbon::now()->format('Y-m-d H:i:s'),],
            ['id' => 5,'name' => 'Student Visa','status' => 'active','created_by' => 16,'created_at' => Carbon::now()->format('Y-m-d H:i:s'),],
            ['id' => 6,'name' => 'Spouse Visa','status' => 'active','created_by' => 16,'created_at' => Carbon::now()->format('Y-m-d H:i:s'),],
            ['id' => 7,'name' => 'Cancelled Visa','status' => 'active','created_by' => 16,'created_at' => Carbon::now()->format('Y-m-d H:i:s'),],
            ['id' => 8,'name' => 'Other','status' => 'active','created_by' => 16,'created_at' => Carbon::now()->format('Y-m-d H:i:s'),],
        ]);
        DB::table('master_sponcerships')->insert([
            ['id' => 1,'name' => 'Milele Motors FZE ','status' => 'active','created_by' => 16,'created_at' => Carbon::now()->format('Y-m-d H:i:s'),],
            ['id' => 2,'name' => 'Milele FZE','status' => 'active','created_by' => 16,'created_at' => Carbon::now()->format('Y-m-d H:i:s'),],
            ['id' => 3,'name' => 'Miele Auto FZE','status' => 'active','created_by' => 16,'created_at' => Carbon::now()->format('Y-m-d H:i:s'),],
            ['id' => 4,'name' => 'Milele Cars Trading LLC','status' => 'active','created_by' => 16,'created_at' => Carbon::now()->format('Y-m-d H:i:s'),],
            ['id' => 5,'name' => 'Father Sponsorship','status' => 'active','created_by' => 16,'created_at' => Carbon::now()->format('Y-m-d H:i:s'),],
            ['id' => 6,'name' => 'Husband Sponsorship','status' => 'active','created_by' => 16,'created_at' => Carbon::now()->format('Y-m-d H:i:s'),],
            ['id' => 7,'name' => 'Golden Visa','status' => 'active','created_by' => 16,'created_at' => Carbon::now()->format('Y-m-d H:i:s'),],
            ['id' => 8,'name' => 'Visa Need to Apply','status' => 'active','created_by' => 16,'created_at' => Carbon::now()->format('Y-m-d H:i:s'),],
        ]);
        DB::table('master_division_with_heads')->insert([
            ['id' => 1,'name' => 'Operations','division_head_id'=> 26,'status' => 'active','created_by' => 16,'created_at' => Carbon::now()->format('Y-m-d H:i:s'),],
            ['id' => 2,'name' => 'Corporate','division_head_id'=> 2,'status' => 'active','created_by' => 16,'created_at' => Carbon::now()->format('Y-m-d H:i:s'),],
            ['id' => 3,'name' => 'Sales','division_head_id'=> 31,'status' => 'active','created_by' => 16,'created_at' => Carbon::now()->format('Y-m-d H:i:s'),],
            ['id' => 4,'name' => 'Demand Planning','division_head_id'=> 46,'status' => 'active','created_by' => 16,'created_at' => Carbon::now()->format('Y-m-d H:i:s'),],
        ]);
        DB::table('master_leaving_reasons')->insert([
            ['id' => 1,'name' => 'Position Redundant','status' => 'active','created_by' => 16,'created_at' => Carbon::now()->format('Y-m-d H:i:s'),],
            ['id' => 2,'name' => 'Personal Reason','status' => 'active','created_by' => 16,'created_at' => Carbon::now()->format('Y-m-d H:i:s'),],
            ['id' => 3,'name' => 'New Opportunity','status' => 'active','created_by' => 16,'created_at' => Carbon::now()->format('Y-m-d H:i:s'),],
            ['id' => 4,'name' => 'Poor Performance','status' => 'active','created_by' => 16,'created_at' => Carbon::now()->format('Y-m-d H:i:s'),],
        ]);
        DB::table('department_head_approvals')->insert([
            ['id' => 1,'department_id' => 1,'department_head_id'=> 10,'approval_by_id' => 10,'status' => 'active','created_by' => 16,'created_at' => Carbon::now()->format('Y-m-d H:i:s'),],
            ['id' => 2,'department_id' => 2,'department_head_id'=> 10,'approval_by_id' => 10,'status' => 'active','created_by' => 16,'created_at' => Carbon::now()->format('Y-m-d H:i:s'),],
            ['id' => 3,'department_id' => 3,'department_head_id'=> 10,'approval_by_id' => 10,'status' => 'active','created_by' => 16,'created_at' => Carbon::now()->format('Y-m-d H:i:s'),],
            ['id' => 4,'department_id' => 4,'department_head_id'=> 10,'approval_by_id' => 10,'status' => 'active','created_by' => 16,'created_at' => Carbon::now()->format('Y-m-d H:i:s'),],
            ['id' => 5,'department_id' => 5,'department_head_id'=> 10,'approval_by_id' => 10,'status' => 'active','created_by' => 16,'created_at' => Carbon::now()->format('Y-m-d H:i:s'),],
            ['id' => 6,'department_id' => 6,'department_head_id'=> 10,'approval_by_id' => 10,'status' => 'active','created_by' => 16,'created_at' => Carbon::now()->format('Y-m-d H:i:s'),],
            ['id' => 7,'department_id' => 7,'department_head_id'=> 10,'approval_by_id' => 10,'status' => 'active','created_by' => 16,'created_at' => Carbon::now()->format('Y-m-d H:i:s'),],
            ['id' => 8,'department_id' => 8,'department_head_id'=> 10,'approval_by_id' => 10,'status' => 'active','created_by' => 16,'created_at' => Carbon::now()->format('Y-m-d H:i:s'),],
            ['id' => 9,'department_id' => 9,'department_head_id'=> 57,'approval_by_id' => 57,'status' => 'active','created_by' => 16,'created_at' => Carbon::now()->format('Y-m-d H:i:s'),],
            ['id' => 10,'department_id' => 10,'department_head_id'=> 10,'approval_by_id' => 10,'status' => 'active','created_by' => 16,'created_at' => Carbon::now()->format('Y-m-d H:i:s'),],
            ['id' => 11,'department_id' => 11,'department_head_id'=> 10,'approval_by_id' => 10,'status' => 'active','created_by' => 16,'created_at' => Carbon::now()->format('Y-m-d H:i:s'),],
            ['id' => 12,'department_id' => 12,'department_head_id'=> 10,'approval_by_id' => 10,'status' => 'active','created_by' => 16,'created_at' => Carbon::now()->format('Y-m-d H:i:s'),],
            ['id' => 13,'department_id' => 13,'department_head_id'=> 10,'approval_by_id' => 10,'status' => 'active','created_by' => 16,'created_at' => Carbon::now()->format('Y-m-d H:i:s'),],
            ['id' => 14,'department_id' => 14,'department_head_id'=> 10,'approval_by_id' => 10,'status' => 'active','created_by' => 16,'created_at' => Carbon::now()->format('Y-m-d H:i:s'),],
            ['id' => 15,'department_id' => 15,'department_head_id'=> 10,'approval_by_id' => 10,'status' => 'active','created_by' => 16,'created_at' => Carbon::now()->format('Y-m-d H:i:s'),],
            ['id' => 16,'department_id' => 16,'department_head_id'=> 10,'approval_by_id' => 10,'status' => 'active','created_by' => 16,'created_at' => Carbon::now()->format('Y-m-d H:i:s'),],
            ['id' => 17,'department_id' => 17,'department_head_id'=> 10,'approval_by_id' => 10,'status' => 'active','created_by' => 16,'created_at' => Carbon::now()->format('Y-m-d H:i:s'),],
            ['id' => 18,'department_id' => 18,'department_head_id'=> 10,'approval_by_id' => 10,'status' => 'active','created_by' => 16,'created_at' => Carbon::now()->format('Y-m-d H:i:s'),],
        ]);
        DB::table('approval_by_positions')->insert([
            ['id' => 1,'approved_by_position' => 'Recruiting Manager','approved_by_id'=> 10,'handover_to_id' => 10,'status' => 'active','created_by' => 16,'created_at' => Carbon::now()->format('Y-m-d H:i:s'),],
            ['id' => 2,'approved_by_position' => 'HR Manager','approved_by_id'=> 10,'handover_to_id' => 10,'status' => 'active','created_by' => 16,'created_at' => Carbon::now()->format('Y-m-d H:i:s'),],
            ['id' => 3,'approved_by_position' => 'Finance Manager','approved_by_id'=> 10,'handover_to_id' => 10,'status' => 'active','created_by' => 16,'created_at' => Carbon::now()->format('Y-m-d H:i:s'),],
        ]);
        DB::table('masters_recuritment_sources')->insert([
            ['id' => 1,'name' => 'Job Boards','status' => 'active','created_by' => 16,'created_at' => Carbon::now()->format('Y-m-d H:i:s'),],
            ['id' => 2,'name' => 'Company Websites','status' => 'active','created_by' => 16,'created_at' => Carbon::now()->format('Y-m-d H:i:s'),],
            ['id' => 3,'name' => 'Social Media Platforms','status' => 'active','created_by' => 16,'created_at' => Carbon::now()->format('Y-m-d H:i:s'),],
            ['id' => 4,'name' => 'Employee Referrals','status' => 'active','created_by' => 16,'created_at' => Carbon::now()->format('Y-m-d H:i:s'),],
            ['id' => 5,'name' => 'Recruiting Agencies','status' => 'active','created_by' => 16,'created_at' => Carbon::now()->format('Y-m-d H:i:s'),],
            ['id' => 6,'name' => 'Networking Events Poaching','status' => 'active','created_by' => 16,'created_at' => Carbon::now()->format('Y-m-d H:i:s'),],
            ['id' => 7,'name' => 'Head Hunting','status' => 'active','created_by' => 16,'created_at' => Carbon::now()->format('Y-m-d H:i:s'),],
        ]);
        DB::table('master_specific_industry_experiences')->insert([
            ['id' => 1,'name' => 'Automative','status' => 'active','created_by' => 16,'created_at' => Carbon::now()->format('Y-m-d H:i:s'),],
            ['id' => 2,'name' => 'Logistics','status' => 'active','created_by' => 16,'created_at' => Carbon::now()->format('Y-m-d H:i:s'),],
            ['id' => 3,'name' => 'Finance','status' => 'active','created_by' => 16,'created_at' => Carbon::now()->format('Y-m-d H:i:s'),],
            ['id' => 4,'name' => 'Consultancy','status' => 'active','created_by' => 16,'created_at' => Carbon::now()->format('Y-m-d H:i:s'),],
        ]);
        DB::table('passport_request_purposes')->insert([
            ['id' => 1,'name' => 'Safekeeping','type' => 'submit','created_at' => Carbon::now()->format('Y-m-d H:i:s'),],
            ['id' => 2,'name' => 'My dealing with Cash','type' => 'submit','created_at' => Carbon::now()->format('Y-m-d H:i:s'),],
            ['id' => 3,'name' => 'My dealing with Sensitive Data','type' => 'submit','created_at' => Carbon::now()->format('Y-m-d H:i:s'),],
            ['id' => 4,'name' => 'Leave','type' => 'release','created_at' => Carbon::now()->format('Y-m-d H:i:s'),],
            ['id' => 5,'name' => 'Passport Renewal','type' => 'release','created_at' => Carbon::now()->format('Y-m-d H:i:s'),],
            ['id' => 6,'name' => 'ATM / Bank','type' => 'release','created_at' => Carbon::now()->format('Y-m-d H:i:s'),],
            ['id' => 7,'name' => 'Embassy Formalities','type' => 'release','created_at' => Carbon::now()->format('Y-m-d H:i:s'),],
            ['id' => 8,'name' => 'Driving License','type' => 'release','created_at' => Carbon::now()->format('Y-m-d H:i:s'),],
            ['id' => 9,'name' => 'Car Registration','type' => 'release','created_at' => Carbon::now()->format('Y-m-d H:i:s'),],
            ['id' => 10,'name' => 'Family Visa/Passport Application','type' => 'release','created_at' => Carbon::now()->format('Y-m-d H:i:s'),],
            ['id' => 11,'name' => 'Visa Application','type' => 'release','created_at' => Carbon::now()->format('Y-m-d H:i:s'),],
            ['id' => 12,'name' => 'E-Gate Card','type' => 'release','created_at' => Carbon::now()->format('Y-m-d H:i:s'),],
            ['id' => 13,'name' => 'Other, please specify','type' => 'release','created_at' => Carbon::now()->format('Y-m-d H:i:s'),],
        ]);
    }
}