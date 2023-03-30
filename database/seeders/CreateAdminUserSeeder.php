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
            // 'password' => bcrypt('123456')
            'password' => Hash::make('123456')
        ]);
        $roles = [
            'Admin'
         ];
      
         foreach ($roles as $role) {
              Role::create(['name' => $role]);
         }
                $roles = Role::all();
    foreach($roles as $role)
    {
        $permissions = Permission::pluck('id','id')->all();
   
        $role->syncPermissions($permissions);
     
        $user->assignRole([$role->id]);
    }
     
        
    }
}
