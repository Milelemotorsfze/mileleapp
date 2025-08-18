<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

//         $this->call(PermissionTableSeeder::class);
//         $this->call(CreateAdminUserSeeder::class);
        // $this->call(AddonTableSeeder::class);
        // $this->call(BrandsTableSeeder::class);
        // // $this->call(AddonDetailsTableSeeder::class);
        // $this->call(PaymentMethodsMasterTableSeeder::class);
        // $this->call(ModelDescriptionMasterTableSeeder::class);
        // $this->call(WarrantyMasterDataSeeder::class);
        // $this->call(StrategySeeder::class);
//         $this->call(KitSeeder::class);
        // $this->call(BrandRegionsSeeder::class);
//        $this->call(PermissionSettingSeeder::class);
//        $this->call(SettingsSeeder::class);
//        $this->call(MasterVendorCategorySeeder::class);
//        $this->call(MasterVendorSubCategorySeeder::class);
        $this->call(LeadSourceSeeder::class);
        $this->call(StrategySeeder::class);
        $this->call(ModelYearCalculationSeeder::class);

    }
}
