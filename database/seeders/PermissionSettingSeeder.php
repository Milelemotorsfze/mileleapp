<?php

namespace Database\Seeders;

use App\Models\Modules;
use App\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

class PermissionSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminRole = Role::find(1);
        $modules = [
            ['24', 'Master Module'],
            ['25', 'Master Permission'],
        ];
        foreach ($modules as $key => $value):
            $module[] = [
                'id'       => $value[0],
                'name' => $value[1]
            ];
        endforeach;
        DB::table('modules')->insert($module);
        $Permissions = [
            // Master Module
            ['24','List Master Modules','master-module-list','To view the list of modules.'],
            ['24','Create Master Module','master-module-create','To create the master module.'],
            ['24','Edit Master Module','master-module-edit','To edit the master module'],
            ['24','Delete Master Module','master-module-delete','To delete the master module'],

            // Permission

            ['25','List Master Permission','master-permission-list','To view the list of permissions.'],
            ['25','Create Master Permission','master-permission-create','To create the master permission.'],
            ['25','Edit Master Permission','master-permission-edit','To edit the master permission'],
            ['25','Delete Master Permission','master-permission-delete','To delete the master permission'],

        ];
        // create entry to permission table and assign permission to admin
        foreach ($Permissions as $key => $value){

            $permission = new Permission();

            $permission->module_id = $value[0];
            $permission->slug_name = $value[1];
            $permission->name = $value[2];
            $permission->guard_name =  'web';
            $permission->description = $value[3];
            $permission->save();

            $data = [
                'permission_id' => $permission->id,
                'role_id' => $adminRole->id
            ];

            DB::table('role_has_permissions')->insert($data);
        }

    }
}
