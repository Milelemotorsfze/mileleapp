<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB; // Import the DB facade here
use App\Models\HRM\Employee\EmployeeProfile;
use App\Models\HRM\Employee\PassportRequest;
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
    protected $appends = [
        'passport_with',
        'can_submit_or_release_passport',
    ];
    public function getPassportWithAttribute() {
        $passportWith = 'with_employee';
        // $passportRequest = PassportRequest::where('employee_id',$this->id)->where('passport_status','with_company')->latest('id')->first();
        // if($passportRequest) {
        //     $passportWith = 'with_company';
        // }
        if($this->passport_status == 'with_milele') {
            $passportWith = 'with_company';
        }
        return $passportWith;
    }
    public function getCanSubmitOrReleasePassportAttribute() {
        $canSubmitOrReleasePassport = false;
        if($this->empProfile->type == 'employee' && ($this->empProfile->passport_status == null OR $this->empProfile->passport_status == 'with_employee')) {
            $isSubmitPending = PassportRequest::where([
                ['employee_id',$this->id],
                ['submit_status','pending'],
            ])->first();
            if($isSubmitPending == null) {
                $canSubmitOrReleasePassport = true;
            }
        }
        else if($this->empProfile->type == 'employee' && $this->empProfile->passport_status == 'with_milele') {
            $isReleasePending = PassportRelease::where([
                ['employee_id',$this->id],
                ['release_submit_status	','pending'],
            ])->first();
            if($isReleasePending == null) {
                $canSubmitOrReleasePassport = true;
            }
        }
        return $canSubmitOrReleasePassport;
    }
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
    public function empProfile()
    {
        return $this->hasOne(EmployeeProfile::class, 'user_id')->where('type','employee');
    }
}
