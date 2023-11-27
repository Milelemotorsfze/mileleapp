<?php

namespace App\Models\HRM\Hiring;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use App\Models\Masters\MasterDepartment;
use App\Models\Masters\MasterOfficeLocation;
use App\Models\User;
use App\Models\Masters\MasterJobPosition;
use App\Models\Masters\MasterExperienceLevel;
use App\Models\HRM\Approvals\ApprovalByPositions;
use App\Models\HRM\Approvals\DepartmentHeadApprovals;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\HRM\Hiring\InterviewSummaryReport;
use Haruncpi\LaravelIdGenerator\IdGenerator;
class EmployeeHiringRequest extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = "employee_hiring_requests";
    protected $fillable = [
        'uuid',
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
        'final_status',

        'closed_by',
        'closed_at',
        'closed_comment',
        'on_hold_by',
        'on_hold_at',
        'on_hold_comment',
        'cancelled_by',
        'cancelled_at',
        'cancelled_comment',

        'action_by_department_head',
        'department_head_id',
        'department_head_action_at',
        'comments_by_department_head',

        'action_by_hiring_manager',
        'hiring_manager_id',
        'hiring_manager_action_at',
        'comments_by_hiring_manager',

        'action_by_division_head',
        'division_head_id',
        'division_head_action_at',
        'comments_by_division_head',

        'action_by_hr_manager',
        'hr_manager_id',
        'hr_manager_action_at',
        'comments_by_hr_manager',

        'created_by',
        'updated_by',
        'deleted_by'
    ];
    public static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            $model->uuid = IdGenerator::generate(['table' => 'employee_hiring_requests','field'=>'uuid', 'length' => 10, 'prefix' =>'EHR-']);
        });
    }
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
        'current_status',
    ];
    public function getDepartmentNameAttribute() {
        $departmentName = '';
        $department = MasterDepartment::find($this->department_id);
        if($department) {
            $departmentName = $department->name;
        }
        return $departmentName;
    }
    public function getDepartmentLocationAttribute() {
        $departmentLocation = '';
        $location = MasterOfficeLocation::find($this->location_id);
        if($location) {
            $departmentLocation = $location->name;
        }
        return $departmentLocation;
    }
    public function getRequestedByNameAttribute() {
        $requestedByName = '';
        $requestedByUser = User::find($this->requested_by);
        if($requestedByUser) {
            $requestedByName = $requestedByUser->name;
        }
        return $requestedByName;
    }
    public function getRequestedJobNameAttribute() {
        $requestedJobName = '';
        $requestedJob = MasterJobPosition::find($this->requested_job_title);
        if($requestedJob) {
            $requestedJobName = $requestedJob->name;
        }
        return $requestedJobName;
    }
    public function getReportingToNameAttribute() {
        $reportingToName = '';
        $reportingTo = User::find($this->reporting_to);
        if($reportingTo) {
            $reportingToName = $reportingTo->name;
        }
        return $reportingToName;
    }
    public function getExperienceLevelNameAttribute() {
        $experienceLevelName = '';
        $experienceLevel = MasterExperienceLevel::find($this->experience_level);
        if($experienceLevel) {
            $experienceLevelName = $experienceLevel->name .' (' .$experienceLevel->number_of_year_of_experience.' )';
        }
        return $experienceLevelName;
    }
    public function getTypeOfRoleNameAttribute() {
        $typeOfRoleName = '';
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
        $createdByName = '';
        $createdBy = User::find($this->created_by);
        if($createdBy) {
            $createdByName = $createdBy->name;
        }
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
    // public function getIsAuthUserCanApproveAttribute() {
    //     $isAuthUserCanApprove = [];
    //     $hiringManager = $HRManager = '';
    //     $deptHead = [];
    //     $authId = Auth::id();
    //     $hiringManager = ApprovalByPositions::where([
    //         ['approved_by_position','Recruiting Manager'],
    //         ['handover_to_id',$authId]
    //     ])->first();
    //     $deptHead = MasterDepartment::where([
    //         ['approval_by_id',$authId],
    //     ])->pluck('id');
    //     $HRManager = ApprovalByPositions::where([
    //         ['approved_by_position','HR Manager'],
    //         ['handover_to_id',$authId]
    //     ])->first();
    //     if($hiringManager && $this->action_by_hiring_manager == 'pending' && $this->hiring_manager_id == $authId) { 
    //         $isAuthUserCanApprove['can_approve'] = true;
    //         $isAuthUserCanApprove['current_approve_position'] = 'Recruiting Manager';
    //         $isAuthUserCanApprove['current_approve_person'] = $this->hiring_manager_name;
    //     }
    //     else if(count($deptHead) > 0 && $this->action_by_hiring_manager == 'approved' && $this->action_by_department_head == 'pending' && 
    //         $this->department_head_id == $authId) {
    //             $isAuthUserCanApprove['can_approve'] = true;
    //             $isAuthUserCanApprove['current_approve_position'] = 'Team Lead / Reporting Manager';
    //             $isAuthUserCanApprove['current_approve_person'] = $this->department_head_name;
    //     }
    //     else if($HRManager && $this->action_by_hiring_manager == 'approved' && $this->action_by_department_head == 'approved' && 
    //         $this->action_by_hr_manager == 'pending' && $this->hr_manager_id == $authId) {
    //             $isAuthUserCanApprove['can_approve'] = true;
    //             $isAuthUserCanApprove['current_approve_position'] = 'HR Manager';
    //             $isAuthUserCanApprove['current_approve_person'] = $this->hr_manager_name;
    //     }
    //     return $isAuthUserCanApprove;
    // }
    public function getIsAuthUserCanApproveAttribute() {
        $isAuthUserCanApprove = [];
        $authId = Auth::id();
        // $hiringManager = ApprovalByPositions::where([
        //     ['approved_by_position','Recruiting Manager'],
        //     ['handover_to_id',$authId]
        // ])->first();
        // $deptHead = MasterDepartment::where([
        //     ['approval_by_id',$authId],
        // ])->pluck('id');
        // $HRManager = ApprovalByPositions::where([
        //     ['approved_by_position','HR Manager'],
        //     ['handover_to_id',$authId]
        // ])->first();
        // dd($this->department_head_id);
        // Approvals =>  Team Lead/Manager -------> Recruitement(Hiring) manager -----------> Division head ---------> HR manager
        if($this->action_by_department_head =='pending' && $this->department_head_id == $authId) {
            $isAuthUserCanApprove['can_approve'] = true;
            $isAuthUserCanApprove['current_approve_position'] = 'Team Lead / Reporting Manager';
            $isAuthUserCanApprove['current_approve_person'] = $this->department_head_name;
        }
        else if($this->action_by_department_head =='approved' && $this->action_by_hiring_manager == 'pending' && $this->hiring_manager_id == $authId) { 
            $isAuthUserCanApprove['can_approve'] = true;
            $isAuthUserCanApprove['current_approve_position'] = 'Recruiting Manager';
            $isAuthUserCanApprove['current_approve_person'] = $this->hiring_manager_name;
        }
        else if($this->action_by_department_head =='approved' && $this->action_by_hiring_manager == 'approved' && $this->action_by_division_head == 'pending' && 
            $this->division_head_id == $authId) {
                $isAuthUserCanApprove['can_approve'] = true;
                $isAuthUserCanApprove['current_approve_position'] = 'Division Head';
                $isAuthUserCanApprove['current_approve_person'] = $this->divisionHead->name;
        }
        else if($this->action_by_department_head =='approved' && $this->action_by_hiring_manager == 'approved' && $this->action_by_division_head == 'approved' && 
            $this->action_by_hr_manager == 'pending' && $this->hr_manager_id == $authId) {
                $isAuthUserCanApprove['can_approve'] = true;
                $isAuthUserCanApprove['current_approve_position'] = 'HR Manager';
                $isAuthUserCanApprove['current_approve_person'] = $this->hr_manager_name;
        }
        return $isAuthUserCanApprove;
    }
    public function getCurrentStatusAttribute() {
        $currentStatus = '';
        if($this->status == 'approved') {
            $currentStatus = 'Approved';
        }
        else if($this->status == 'rejected') {
            $currentStatus = 'Rejected';
        }
        // Approvals =>  Team Lead/Manager -------> Recruitement(Hiring) manager -----------> Division head ---------> HR manager
        else if($this->status == 'pending' && $this->action_by_department_head == 'pending') {
            $currentStatus = "Team Lead's /Reporting Manager's Approval Awaiting";
        }
        else if($this->status == 'pending' && $this->action_by_hiring_manager == 'pending') {
            $currentStatus = "Recruiting Manager's Approval Awaiting";
        }
        else if($this->status == 'pending' && $this->action_by_division_head == 'pending') {
            $currentStatus = "Division Head's Approval Awaiting";
        }
        else if($this->status == 'pending' && $this->action_by_hr_manager == 'pending') {
            $currentStatus = "HR Manager's Approval Awaiting";
        }
        return $currentStatus;
    }
    public function history() {
        return $this->hasMany(EmployeeHiringRequestHistory::class,'hiring_request_id','id');
    }
    public function questionnaire() {
        return $this->hasOne(EmployeeHiringQuestionnaire::class,'hiring_request_id','id');
    }
    public function jobDescription() {
        return $this->hasOne(JobDescription::class,'hiring_request_id','id');
    }
    public function divisionHead() {
        return $this->hasOne(User::class,'id','division_head_id');
    }
    public function interviewSummaryReport() {
        return $this->hasMany(InterviewSummaryReport::class,'hiring_request_id','id');
    }
    public function shortlistedCandidates() {
        return $this->hasMany(InterviewSummaryReport::class,'hiring_request_id','id')->where('candidate_selected','yes');
    }
    public function selectedCandidates() {
        return $this->hasMany(InterviewSummaryReport::class,'hiring_request_id','id')->where('candidate_selected','yes')->where('seleced_status','selected');
    }
    public function location() {
        return $this->hasOne(MasterOfficeLocation::class,'id','location_id');
    }
}
