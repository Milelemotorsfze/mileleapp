<?php

namespace App\Models\HRM\Hiring;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use App\Models\Masters\MasterDeparment;
use App\Models\Masters\MasterOfficeLocation;
use App\Models\User;
use App\Models\Masters\MasterJobPosition;
use App\Models\Masters\MasterExperienceLevel;
use App\Models\HRM\Approvals\ApprovalByPositions;
use App\Models\HRM\Approvals\DepartmentHeadApprovals;
class EmployeeHiringRequest extends Model
{
    use HasFactory;
    protected $table = "employee_hiring_requests";
    protected $fillable = [
        'request_date',
        'department_id',
        'location_id',
        'requested_by',
        'requested_job_title',
        'reporting_to',
        'number_of_openings',
        'salary_range_start_in_aed',
        'salary_range_end_in_aed',
        'experience_level',
        'work_time_start',
        'work_time_end',
        'type_of_role',
        'replacement_for_employee',
        'explanation_of_new_hiring',
        'status',
        'action_by_hiring_manager',
        'hiring_manager_id',
        'hiring_manager_action_at',
        'comments_by_hiring_manager',
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
        'deleted_by'
    ];
    protected $appends = [
        'department_name',
        'department_location',
        'requested_by_name',
        'requested_job_name',
        'reporting_to_name',
        'experience_level_name',
        'type_of_role_name',
        'replacement_for_employee_name',
        'created_by_name',
        'hiring_manager_name',
        'department_head_name',
        'department_head_email',
        'hr_manager_name',
        'hr_manager_email',
        'is_auth_user_can_approve',
    ];
    public function getDepartmentNameAttribute() {
        $department = MasterDeparment::find($this->department_id);
        $departmentName = $department->name;
        return $departmentName;
    }
    public function getDepartmentLocationAttribute() {
        $location = MasterOfficeLocation::find($this->location_id);
        $departmentLocation = $location->name;
        return $departmentLocation;
    }
    public function getRequestedByNameAttribute() {
        $requestedByUser = User::find($this->requested_by);
        $requestedByName = $requestedByUser->name;
        return $requestedByName;
    }
    public function getRequestedJobNameAttribute() {
        $requestedJob = MasterJobPosition::find($this->requested_job_title);
        $requestedJobName = $requestedJob->name;
        return $requestedJobName;
    }
    public function getReportingToNameAttribute() {
        $reportingTo = User::find($this->reporting_to);
        $reportingToName = $reportingTo->name;
        return $reportingToName;
    }
    public function getExperienceLevelNameAttribute() {
        $experienceLevel = MasterExperienceLevel::find($this->experience_level);
        $experienceLevelName = $experienceLevel->name .' (' .$experienceLevel->number_of_year_of_experience.' )';
        return $experienceLevelName;
    }
    public function getTypeOfRoleNameAttribute() {
        if($this->type_of_role == 'new_position') {
            $typeOfRoleName = 'New Position';
        }
        else if($this->type_of_role == 'replacement') {
            $typeOfRoleName = 'Replacement';
        }
        return $typeOfRoleName;
    }
    public function getReplacementForEmployeeNameAttribute() {
        $replacementForEmployeeName = '';
        if($this->replacement_for_employee != NULL) {
            $replacementForEmployee = User::find($this->replacement_for_employee);
            $replacementForEmployeeName = $replacementForEmployee->name;
            return $replacementForEmployeeName;
        }
    }
    public function getCreatedByNameAttribute() {
        $createdBy = User::find($this->created_by);
        $createdByName = $createdBy->name;
        return $createdByName;
    }
    public function getHiringManagerNameAttribute() {
        $hiringManagerName = '';
        $hiringManager = User::find($this->hiring_manager_id);
        if($hiringManager) {
            $hiringManagerName = $hiringManager->name;
        }
        return $hiringManagerName;
    }
    public function getDepartmentHeadNameAttribute() {
        $departmentHeadName = '';
        $departmentHead = User::find($this->department_head_id);
        if($departmentHead) {
            $departmentHeadName = $departmentHead->name;
        }
        return $departmentHeadName;
    }
    public function getDepartmentHeadEmailAttribute() {
        $departmentHeadEmail = '';
        $departmentHeaduser = User::find($this->department_head_id);
        if($departmentHeaduser) {
            $departmentHeadEmail = $departmentHeaduser->name;
        }
        return $departmentHeadEmail;
    }
    public function getHRManagerNameAttribute() {
        $HRManagerName = '';
        $HRManager = User::find($this->hr_manager_id);
        if($HRManager) {
            $HRManagerName = $HRManager->name;
        }
        return $HRManagerName;
    }
    public function getHRManagerEmailAttribute() {
        $HRManagerEmail = '';
        $HRManageruser = User::find($this->department_head_id);
        if($HRManageruser) {
            $HRManagerEmail = $HRManageruser->name;
        }
        return $HRManagerEmail;
    }
    public function getIsAuthUserCanApproveAttribute() {
        $isAuthUserCanApprove = [];
        $hiringManager = $HRManager = '';
        $deptHead = [];
        $authId = Auth::id();
        $hiringManager = ApprovalByPositions::where([
            ['approved_by_position','Hiring Manager'],
            ['handover_to_id',$authId]
        ])->first();
        $deptHead = DepartmentHeadApprovals::where([
            ['approval_by_id',$authId],
        ])->pluck('department_id');
        $HRManager = ApprovalByPositions::where([
            ['approved_by_position','HR Manager'],
            ['handover_to_id',$authId]
        ])->first();
        if($hiringManager && $this->action_by_hiring_manager == 'pending' && $this->hiring_manager_id == $authId) { 
            $isAuthUserCanApprove['can_approve'] = true;
            $isAuthUserCanApprove['current_approve_position'] = 'Hiring Manager';
            $isAuthUserCanApprove['current_approve_person'] = $this->hiring_manager_name;
        }
        else if(count($deptHead) > 0 && $this->action_by_hiring_manager == 'approved' && $this->action_by_department_head == 'pending' && 
            $this->department_head_id == $authId) {
                $isAuthUserCanApprove['can_approve'] = true;
                $isAuthUserCanApprove['current_approve_position'] = 'Department Head';
                $isAuthUserCanApprove['current_approve_person'] = $this->department_head_name;
        }
        else if($HRManager && $this->action_by_hiring_manager == 'approved' && $this->action_by_department_head == 'approved' && 
            $this->action_by_hr_manager == 'pending' && $this->hr_manager_id == $authId) {
                $isAuthUserCanApprove['can_approve'] = true;
                $isAuthUserCanApprove['current_approve_position'] = 'HR Manager';
                $isAuthUserCanApprove['current_approve_person'] = $this->hr_manager_name;
        }
        return $isAuthUserCanApprove;
    }
    public function history()
    {
        return $this->hasMany(EmployeeHiringRequestHistory::class,'hiring_request_id','id');
    }
}
