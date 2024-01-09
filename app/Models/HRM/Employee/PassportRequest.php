<?php

namespace App\Models\HRM\Employee;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\User;
use App\Models\Masters\PassportRequestPurpose;
use App\Models\HRM\Employee\PassportRequestHistory;
use Illuminate\Support\Facades\Auth;

class PassportRequest extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = "passport_requests";
    protected $fillable = [
        'employee_id',
        'passport_status',
        'purposes_of_submit',
        'submit_status',
        'submit_action_by_employee',
        'submit_employee_action_at',
        'submit_comments_by_employee',
        'submit_action_by_department_head',
        'submit_department_head_id',
        'submit_department_head_action_at',
        'submit_comments_by_department_head',
        'submit_action_by_division_head',
        'submit_division_head_id',
        'submit_division_head_action_at',
        'submit_comments_by_division_head',
        'submit_action_by_hr_manager',
        'submit_hr_manager_id',
        'submit_hr_manager_action_at',
        'submit_comments_by_hr_manager',
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
        if($this->submit_action_by_employee =='pending' && $this->employee_id == $authId) {
            $isAuthUserCanApprove['can_approve'] = true;
            $isAuthUserCanApprove['current_approve_position'] = 'Employee';
            $isAuthUserCanApprove['current_approve_person'] = $this->user->name;
        }
        else if($this->submit_action_by_employee =='approved' && $this->submit_action_by_department_head == 'pending' && $this->submit_department_head_id == $authId) { 
            $isAuthUserCanApprove['can_approve'] = true;
            $isAuthUserCanApprove['current_approve_position'] = 'Reporting Manager';
            $isAuthUserCanApprove['current_approve_person'] = $this->reportingManager->name;
        }
        else if($this->submit_action_by_employee =='approved' && $this->submit_action_by_department_head == 'approved' && $this->submit_action_by_division_head == 'pending' && 
            $this->submit_division_head_id == $authId) {
                $isAuthUserCanApprove['can_approve'] = true;
                $isAuthUserCanApprove['current_approve_position'] = 'Division Head';
                $isAuthUserCanApprove['current_approve_person'] = $this->divisionHead->name;
        }
        else if($this->submit_action_by_employee =='approved' && $this->submit_action_by_department_head == 'approved' && $this->submit_action_by_division_head == 'approved' && 
            $this->submit_action_by_hr_manager == 'pending' && $this->submit_hr_manager_id == $authId) {
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
        return $this->hasOne(PassportRequestPurpose::class,'id','purposes_of_submit');
    }
    public function reportingManager() {
        return $this->hasOne(User::class,'id','submit_department_head_id');
    }
    public function divisionHead() {
        return $this->hasOne(User::class,'id','submit_division_head_id');
    }
    public function hrManager() {
        return $this->hasOne(User::class,'id','submit_hr_manager_id');
    }
    public function history() {
        return $this->hasMany(PassportRequestHistory::class,'passport_request_id','id');
    }
    public function allReleases() {
        return $this->hasMany(PassportRelease::class,'passport_request_id','id');
    }
    public function approvedRelease() {
        return $this->hasOne(PassportRelease::class,'passport_request_id','id')->where('release_submit_status','!=','rejected');
    }
}
