<?php

namespace App\Models\HRM\Hiring;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Masters\MasterOfficeLocation;
use App\Models\Masters\MasterJobPosition;
use App\Models\Masters\MasterDepartment;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class JobDescription extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = "job_descriptions";
    protected $fillable = [
        'hiring_request_id',
        'request_date',
        'location_id',
        'job_purpose',
        'duties_and_responsibilities',
        'skills_required',
        'position_qualification',
        'status',
        'action_by_department_head',
        'department_head_id',
        'department_head_action_at',
        'comments_by_department_head',
        'action_by_hr_manager',
        'hr_manager_id',
        'hr_manager_action_at',
        'comments_by_hr_manager',
        'created_by',
        'updated_by',
        'deleted_by',
    ];
    protected $appends = [
        'is_auth_user_can_approve',
    ];
    public function getIsAuthUserCanApproveAttribute() {
        $isAuthUserCanApprove = [];
        $authId = Auth::id();
        if($this->action_by_department_head =='pending' && $this->department_head_id == $authId) {
            $isAuthUserCanApprove['can_approve'] = true;
            $isAuthUserCanApprove['current_approve_position'] = 'Team Lead / Reporting Manager';
            $isAuthUserCanApprove['current_approve_person'] = $this->departmentHeadName->name;
        }
        else if($this->action_by_department_head =='approved' && $this->action_by_hr_manager == 'pending' && $this->hr_manager_id == $authId) {
                $isAuthUserCanApprove['can_approve'] = true;
                $isAuthUserCanApprove['current_approve_position'] = 'HR Manager';
                $isAuthUserCanApprove['current_approve_person'] = $this->hrManagerName->name;
        }
        return $isAuthUserCanApprove;
    }
    public function jobTitle() {
        return $this->hasOne(MasterJobPosition::class,'id','job_title');
    }
    public function department() {
        return $this->hasOne(MasterDepartment::class,'id','department_id');
    }
    public function location() {
        return $this->hasOne(MasterOfficeLocation::class,'id','location_id');
    }
    public function reportingTo() {
        return $this->hasOne(User::class,'id','reporting_to');
    }
    public function employeeHiringRequest() {
        return $this->hasOne(EmployeeHiringRequest::class,'id','hiring_request_id');
    }
    public function createdBy() {
        return $this->hasOne(User::class,'id','created_by');
    }
    public function departmentHeadName() {
        return $this->hasOne(User::class,'id','department_head_id');
    }
    public function hrManagerName() {
        return $this->hasOne(User::class,'id','hr_manager_id');
    }
}
