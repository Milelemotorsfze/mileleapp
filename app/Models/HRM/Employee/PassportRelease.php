<?php

namespace App\Models\HRM\Employee;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Masters\PassportRequestPurpose;
use App\Models\HRM\Employee\PassportReleaseHistory;
use Illuminate\Support\Facades\Auth;

class PassportRelease extends Model
{
    use HasFactory;
    protected $table = "passport_releases";
    protected $fillable = [
        'employee_id',
        'passport_request_id',
        'purposes_of_release',
        'release_purpose',
        'release_submit_status',
        'release_action_by_employee',
        'release_employee_action_at',
        'release_comments_by_employee',
        'release_action_by_department_head',
        'release_department_head_id',
        'release_department_head_action_at',
        'release_comments_by_department_head',
        'release_action_by_division_head',
        'release_division_head_id',
        'release_division_head_action_at',
        'release_comments_by_division_head',
        'release_action_by_hr_manager',
        'release_hr_manager_id',
        'release_hr_manager_action_at',
        'release_comments_by_hr_manager',
        'created_by',
        'updated_by',
        'deleted_by'
    ];
    protected $appends = [
        'is_auth_user_can_approve',
    ];
    public function getIsAuthUserCanApproveAttribute() {
        $isAuthUserCanApprove = [];
        $authId = Auth::id();
         // employee -------> Reporting Manager  ------------>Division Head--------->HR Manager
        if($this->release_action_by_employee =='pending' && $this->employee_id == $authId) {
            $isAuthUserCanApprove['can_approve'] = true;
            $isAuthUserCanApprove['current_approve_position'] = 'Employee';
            $isAuthUserCanApprove['current_approve_person'] = $this->user->name;
        }
        else if($this->release_action_by_employee =='approved' && $this->release_action_by_department_head == 'pending' && $this->release_department_head_id == $authId) { 
            $isAuthUserCanApprove['can_approve'] = true;
            $isAuthUserCanApprove['current_approve_position'] = 'Reporting Manager';
            $isAuthUserCanApprove['current_approve_person'] = $this->reportingManager->name;
        }
        else if($this->release_action_by_employee =='approved' && $this->release_action_by_department_head == 'approved' && $this->release_action_by_division_head == 'pending' && 
            $this->release_division_head_id == $authId) {
                $isAuthUserCanApprove['can_approve'] = true;
                $isAuthUserCanApprove['current_approve_position'] = 'Division Head';
                $isAuthUserCanApprove['current_approve_person'] = $this->divisionHead->name;
        }
        else if($this->release_action_by_employee =='approved' && $this->release_action_by_department_head == 'approved' && $this->release_action_by_division_head == 'approved' && 
            $this->release_action_by_hr_manager == 'pending' && $this->release_hr_manager_id == $authId) {
                $isAuthUserCanApprove['can_approve'] = true;
                $isAuthUserCanApprove['current_approve_position'] = 'HR Manager';
                $isAuthUserCanApprove['current_approve_person'] = $this->hrManager->name;
        }
        return $isAuthUserCanApprove;
    }
    public function user() {
        return $this->hasOne(User::class,'id','employee_id');
    }
    public function purpose() {
        return $this->hasOne(PassportRequestPurpose::class,'id','purposes_of_release');
    }
    public function reportingManager() {
        return $this->hasOne(User::class,'id','release_department_head_id');
    }
    public function divisionHead() {
        return $this->hasOne(User::class,'id','release_division_head_id');
    }
    public function hrManager() {
        return $this->hasOne(User::class,'id','release_hr_manager_id');
    }
    public function history() {
        return $this->hasMany(PassportReleaseHistory::class,'passport_release_id','id');
    }
}
