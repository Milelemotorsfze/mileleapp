<?php

namespace Database\Seeders;
  
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use DB;
  
class PermissionTableSeeder extends Seeder
{
    public function run()
    {
        $modules = [
            ['1','User'],
            ['2','Role']
            ];
        foreach ($modules as $key => $value):
        $module[] = [
            'id'       => $value[0],
            'name' => $value[1]
        ];
        endforeach ;
        DB::table('modules')->insert($module);

        $Permissions = [
            ['1','List Active User','user-list-active','To view the list of active users.'],
            ['1','List Inactive User','user-list-inactive','To view the list of inactive users.'],
            ['1','List Deleted User','user-list-deleted','To view the list of deleted users.'],
            ['1','Make User Inactive','user-make-inactive','To make an active user to inactive for preventing login and other activities.'],
            ['1','Make User Active','user-make-active','To make an inactive user to active for allow login and other activities.'],
            ['1','Restore User','user-restore','To restore a deleted user.'],
            ['1','View User Details','user-view','To view the user details.'],          
            ['1','Create User','user-create','To create a new user.'],
            ['1','Edit User','user-edit','To edit a user.'],
            ['1','Delete User','user-delete','To delete a user.'],

            ['2','List Role','role-list','To view the list of all roles.'],
            ['2','View Role Details', 'role-view','To view the role details with permissions.'],
            ['2','Create Role','role-create','To create a new role with permissions.'],
            ['2','Edit Role','role-edit','To edit a role with permissions.'],
            ['2','Delete Role','role-delete','To delete a role.'],
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

