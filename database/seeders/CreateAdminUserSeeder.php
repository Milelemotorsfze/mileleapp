<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Hash;

class CreateAdminUserSeeder extends Seeder
{
    public function run()
    {
        $user = User::create([
            'name' => 'Hardik Savani',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('123456')
        ]);

        $roles = [
            1 => 'Admin',
            2 => 'Management',
            3 => 'Marketing Executive',
            4 => 'Marketing Manager',
            5 => 'Warehouse Executive',
            6 => 'Warehouse Manager',
            7 => 'Sales Executive',
            8 => 'Sales Manager',
            9 => 'Procurement Executive',
            10 => 'Procurement Manager',
            11 => 'Logistics Executive',
            12 => 'Logistics Manager',
            13 => 'QC Executive',
            14 => 'QC Manager',
            15 => 'Part Procurement Executive',
            16 => 'Part Procurement Manager',
            17 => 'Demand & Planning Executive',
            18 => 'Demand & Planning Manager',
            19 => 'HR Executive',
            20 => 'HR Manager',
            21 => 'Finance Executive',
            22 => 'Finance Manager',
        ];

        foreach ($roles as $roleId => $roleName) {
            Role::create(['id' => $roleId, 'name' => $roleName]);
        }

        $adminRole = Role::find(1);
        $permissions = Permission::pluck('id')->all();
        $adminRole->syncPermissions($permissions);
        $user->assignRole([$adminRole->id]);

        $managementRole = Role::find(2);
        $managementPermissions = Permission::whereIn('name', [
            'user-list-active',
            'user-list-inactive',
            'user-make-inactive',
            'user-make-active',
            'user-restore',
            'user-view',
            'user-create',
            'user-edit',
            'user-delete',
            'role-list',
            'role-view',
            'role-create',
            'role-edit',
            'role-delete',
        ])->pluck('id')->all();
        $managementRole->syncPermissions($managementPermissions);
    }
}