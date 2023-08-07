<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;

class KitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $modules = [
            ['22', 'Master Brand'],
            ['23', 'Master Model Line'],
            ];
        foreach ($modules as $key => $value):
        $module[] = [
            'id'       => $value[0],
            'name' => $value[1]
        ];
        endforeach ;
        DB::table('modules')->insert($module);
        $Permissions = [
             //Kit
            //  ['20','Kit List','kit-list', 'To View List of Kit'],
            //  ['20','View Addon Kit Item Purchase Price','view-addon-kit-item-purchase-price', 'To View Addon Kit Item Purchase Price'],
            // Master Kit
            // ['12','Master Addons Create','master-addon-create', 'To Create the Master-Addons'],
            ['20','Master Kit Create','master-kit-create', 'To Create the Master Kits'],
             // Kits
            //  ['14','Addons Create','addon-create', 'To Create the Addons'],
            // ['20','Kit Create','kit-create', 'To Create the Kits'],
            //  ['14','Addons Edit','addon-edit', 'To Edit the Addons'],
            ['20','Kits Edit','kit-edit', 'To Edit the kits'],
            //  ['14','Addons Delete','addon-delete', 'To Delete the Addons'],
            //  ['14','Addons View','addon-view', 'To View the Addons'],
            //  ['14','Addons Active','addon-active-inactive', 'To Activate and Inactivate the Addons'],

             // Suppliers
            //  ['10','Supplier List','addon-supplier-list', 'To View List of Suppliers'],
            //  ['10','Supplier Create','addon-supplier-create', 'To Create the Suppliers'],
            //  ['10','Supplier Edit','addon-supplier-edit', 'To Edit the Suppliers'],
            //  ['10','Supplier Delete','addon-supplier-delete', 'To Delete the Supplier'],
            //  ['10','Supplier View','addon-supplier-view', 'To View the Supplier'],
            //  ['10','Supplier Active','supplier-active-inactive', 'To Activate and Inactivate the Supplier'],


           

            //Addon Purchase Prices
            // ['15','Addons Purchase Price','addon-purchase-price', 'To View Addon Purchase Price'],
            // ['15','View Addon Least Purchase Price','addon-least-purchase-price-view', 'To View Addon Least Purchase Price'],
            // Addon Selling Prices
            // ['16','View Addon Selling Price','addon-selling-price-view', 'To View Addon Selling Price'],
            // ['16','Edit Addon New Selling Price','edit-addon-new-selling-price', 'To Edit Addon New Selling Price'],
            // ['16','Approve Addon New Selling Price','approve-addon-new-selling-price', 'To Approve Addon New Selling Price'],
            // ['16','Reject Addon New Selling Price','reject-addon-new-selling-price', 'To Reject Addon New Selling Price'],
            // ['16','Add New Addon Selling Price','add-new-addon-selling-price', 'To Add New Addon Selling Price'],
            // ['16','View Addon Selling Price History','view-addon-selling-price-history', 'To View Addon Selling Price History'],

            // Supplier Addons
            // ['17','Supplier Add New Purchase Price','supplier-new-purchase-price', 'To Add New Purchase Price'],
            // ['17','Delete Suplier Addon','supplier-addon-delete', 'To Delete Supplier Addon'],
            // ['17','Approve OR Reject New Price','supplier-price-action', 'To Approve OR Reject New purchase Price'],
            // ['17','View Addons and Prices','supplier-addon-price', 'View Addons and Prices'],
            // ['17','View Supplier Addon Purchase Price History','supplier-addon-purchase-price-history', 'To View Supplier Addon Purchase Price History'],
            // ['17','View Supplier Addon Purchase Price','supplier-addon-purchase-price-view', 'To View Supplier Addon Purchase Price'],




            // Brand
            ['22','View Master Brand List','view-brand-list', 'To View List of Master Brands'],
            ['22','Create Master Brand','master-brand-create', 'To Create Master Brand'],
            ['22','Edit Master Brand','master-brand-edit', 'To Edit Master Brand'],

            // Model Line
            ['23','View Master Model Lines List','view-model-lines-list', 'To View List of Master Model Lines'],
            ['23','Create Master Model Lines','master-model-lines-create', 'To Create Master Model Lines'],
            ['23','Edit Master Model Lines','master-model-lines-edit', 'To Edit Master Model Lines'],
            ];
        foreach ($Permissions as $key => $value):
        $permission[] = [
            'module_id'       => $value[0],
            'slug_name' => $value[1],
            'name' => $value[2],
            'guard_name' => 'web',
            'description' => $value[3]
        ];
        endforeach ;
        DB::table('permissions')->insert($permission);
    }
}
