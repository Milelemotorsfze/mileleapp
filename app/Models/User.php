<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB; // Import the DB facade here

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles, SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'password',
        'selected_role', // Add the selected_role column here
        'sales_rap',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    public function getSelectedRoleAttribute()
    {
        return $this->attributes['selected_role'] ?? $this->roles()->first()->name;
    }
    public function hasPermissionForSelectedRole($permissionName)
    {
        $selectedRole = $this->selected_role;
        if(is_array($permissionName)) {
            if(count($permissionName) > 0)
            {
                return DB::table('role_has_permissions')
                    ->join('permissions', 'role_has_permissions.permission_id', '=', 'permissions.id')
                    ->where('role_has_permissions.role_id', $selectedRole)
                    ->whereIn('permissions.name', $permissionName)
                    ->exists();
            }
        }
        else{
            if ($selectedRole) {
                return DB::table('role_has_permissions')
                    ->join('permissions', 'role_has_permissions.permission_id', '=', 'permissions.id')
                    ->where('role_has_permissions.role_id', $selectedRole)
                    ->where('permissions.name', $permissionName)
                    ->exists();
            }
        }
        
        return false;
    }
}