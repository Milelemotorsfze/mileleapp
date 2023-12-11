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
        if($this->type == 'employee' && $this->passport_status == NULL) {
            $submitRequestExist = PassportRequest::where('employee_id',$this->id)->first();
            $isSubmitPending = PassportRequest::where([
                ['employee_id',$this->id],
                ['submit_status','pending'],
            ])->first();
        }
        else if($this->type == 'employee' && $this->passport_status == 'with_milele') {

        }
        else if($this->type == 'employee' && $this->passport_status == 'with_employee') {
            
        }
        // $submitRequestExist = PassportRequest::where('employee_id',$this->id)->first();
        // $isSubmitPending = PassportRequest::where([
        //     ['employee_id',$this->id],
        //     ['submit_status','pending'],
        // ])->first();
        // $isSubmited = PassportRequest::where([
        //     ['employee_id',$this->id],
        //     ['passport_status','with_company'],
        //     ['submit_status','approved'],
        //     ['release_submit_status',NULL]
        // ])->first();
        // $isReleasePending = PassportRequest::where([
        //     ['employee_id',$this->id],
        //     ['passport_status','with_company'],
        //     ['submit_status','approved'],
        //     ['release_submit_status','pending']
        // ])->first();
        // $canSubmitOrReleasePassport = true;
        
        // if($empExist) {
            

            // $isRelease = PassportRequest::where([
            //     ['employee_id',$this->id],
            //     ['passport_status','with_company'],
            //     ['passport_status','with_company'],
            //     ['release_submit_status','!=',NULL]
            // ])->first();
            // if(!$isSubmit) {
            //     $canSubmitOrReleasePassport = true;
            // }
                // ['passport_status','with_company'],
                // ['submit_status','!=','rejected'],
                // ['release_submit_status','!=',NULL],
            // )->where('submit_status','!=','rejected')->orWhereNotIn('release_submit_status',[NULL,'rejected'])->get();

            
        // }
        // else {
        //     $canSubmitOrReleasePassport = true;
        // }
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
        return $this->hasOne(EmployeeProfile::class, 'user_id');
    }
}
