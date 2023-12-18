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
use App\Models\HRM\Hiring\EmployeeHiringRequest;
use App\Models\HRM\Hiring\JobDescription;
use App\Models\HRM\Hiring\InterviewSummaryReport;
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
        'hiring_request_approval',
        'job_description_approval',
        'interview_summary_report_approval',
        // 'candidate_personal_information_varify',
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
        if($this->id != 16) {
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
        }
        return $canSubmitOrReleasePassport;
    }
    public function getHiringRequestApprovalAttribute() {
        $hiringRequestApproval = false;
        $hiringManagerPendings = $hiringManagerApproved = $hiringManagerRejected = $deptHeadPendings = $deptHeadApproved = $deptHeadRejected = $HRManagerPendings 
        = $HRManagerApproved = $HRManagerRejected = [];
        $hiringManagerPendings = EmployeeHiringRequest::where([
            ['action_by_hiring_manager','pending'],
            ['hiring_manager_id',$this->id],
            ])->latest()->get();
        $hiringManagerApproved = EmployeeHiringRequest::where([
            ['action_by_hiring_manager','approved'],
            ['hiring_manager_id',$this->id],
            ])->latest()->get();
        $hiringManagerRejected = EmployeeHiringRequest::where([
            ['action_by_hiring_manager','rejected'],
            ['hiring_manager_id',$this->id],
            ])->latest()->get();
        $deptHeadPendings = EmployeeHiringRequest::where([
            ['action_by_hiring_manager','approved'],
            ['action_by_department_head','pending'],
            ['department_head_id',$this->id],
            ])->latest()->get();
        $deptHeadApproved = EmployeeHiringRequest::where([
            ['action_by_hiring_manager','approved'],
            ['action_by_department_head','pending'],
            ['department_head_id',$this->id],
            ])->latest()->get();
        $deptHeadRejected = EmployeeHiringRequest::where([
            ['action_by_hiring_manager','approved'],
            ['action_by_department_head','pending'],
            ['department_head_id',$this->id],
            ])->latest()->get();
        $HRManagerPendings = EmployeeHiringRequest::where([
            ['action_by_hiring_manager','approved'],
            ['action_by_department_head','approved'],
            ['action_by_hr_manager','pending'],
            ['hr_manager_id',$this->id],
            ])->latest()->get();
        $HRManagerApproved = EmployeeHiringRequest::where([
            ['action_by_hiring_manager','approved'],
            ['action_by_department_head','approved'],
            ['action_by_hr_manager','pending'],
            ['hr_manager_id',$this->id],
            ])->latest()->get();
        $HRManagerRejected = EmployeeHiringRequest::where([
            ['action_by_hiring_manager','approved'],
            ['action_by_department_head','approved'],                
            ['action_by_hr_manager','pending'],
            ['hr_manager_id',$this->id],
            ])->latest()->get();
        if(count($hiringManagerPendings) > 0 OR count($hiringManagerApproved) > 0 OR count($hiringManagerRejected) > 0 OR count($deptHeadPendings) > 0 OR 
        count($deptHeadApproved) > 0 OR count($deptHeadRejected) > 0 OR count($HRManagerPendings) > 0 OR count($HRManagerApproved) > 0 OR count($HRManagerRejected) > 0) {
            $hiringRequestApproval = true;
        }
        return $hiringRequestApproval;
    }
    public function getJobDescriptionApprovalAttribute() {
        $jobDescriptionApproval = false;
        $deptHeadPendings = $deptHeadApproved = $deptHeadRejected = $HRManagerPendings = $HRManagerApproved = $HRManagerRejected = [];
        $deptHeadPendings = JobDescription::where([
            ['action_by_department_head','pending'],
            ['department_head_id',$this->id],
            ])->latest()->get();
        $deptHeadApproved = JobDescription::where([
            ['action_by_department_head','approved'],
            ['department_head_id',$this->id],
            ])->latest()->get();
        $deptHeadRejected = JobDescription::where([
            ['action_by_department_head','rejected'],
            ['department_head_id',$this->id],
            ])->latest()->get();
        $HRManagerPendings = JobDescription::where([
            ['action_by_department_head','approved'],
            ['action_by_hr_manager','pending'],
            ['hr_manager_id',$this->id],
            ])->latest()->get();
        $HRManagerApproved = JobDescription::where([
            ['action_by_department_head','approved'],
            ['action_by_hr_manager','approved'],
            ['hr_manager_id',$this->id],
            ])->latest()->get();
        $HRManagerRejected = JobDescription::where([
            ['action_by_department_head','approved'],                
            ['action_by_hr_manager','rejected'],
            ['hr_manager_id',$this->id],
            ])->latest()->get();
        if(count($deptHeadPendings) > 0 OR count($deptHeadApproved) > 0 OR count($deptHeadRejected) > 0 OR count($HRManagerPendings) > 0 OR 
            count($HRManagerApproved) > 0 OR count($HRManagerRejected) > 0) {
                $jobDescriptionApproval = true;
            }
        return $jobDescriptionApproval;
    }
    public function getInterviewSummaryReportApprovalAttribute() {
        $interviewSummaryReportApproval = false;
        $divisionHeadPendings = $divisionHeadApproved = $divisionHeadRejected = $HRManagerPendings = $HRManagerApproved = $HRManagerRejected = [];
        $HRManagerPendings = InterviewSummaryReport::where([
            ['action_by_hr_manager','pending'],
            ['hr_manager_id',$this->id],
            ])->latest()->get();
        $HRManagerApproved = InterviewSummaryReport::where([
            ['action_by_hr_manager','approved'],
            ['hr_manager_id',$this->id],
            ])->latest()->get();
        $HRManagerRejected = InterviewSummaryReport::where([
            ['action_by_hr_manager','rejected'],
            ['hr_manager_id',$this->id],
            ])->latest()->get();
        $divisionHeadPendings = InterviewSummaryReport::where([
            ['action_by_hr_manager','approved'],
            ['action_by_division_head','pending'],
            ['hr_manager_id',$this->id],
            ])->latest()->get();
        $divisionHeadApproved = InterviewSummaryReport::where([
            ['action_by_hr_manager','approved'],
            ['action_by_division_head','approved'],
            ['hr_manager_id',$this->id],
            ])->latest()->get();
        $divisionHeadRejected = InterviewSummaryReport::where([
            ['action_by_hr_manager','approved'],
            ['action_by_division_head','rejected'],                
            ['hr_manager_id',$this->id],
            ])->latest()->get();
        if(count($HRManagerPendings) > 0 OR count($HRManagerApproved) > 0 OR count($HRManagerRejected) > 0 OR count($divisionHeadPendings) > 0 OR 
        count($divisionHeadApproved) > 0 OR count($divisionHeadRejected) > 0) {
            $interviewSummaryReportApproval = true;
        }
        return $interviewSummaryReportApproval;
    }
    public function getSelectedRoleAttribute() {
        return $this->attributes['selected_role'] ?? $this->roles()->first()->name;
    }
    public function hasPermissionForSelectedRole($permissionName) {
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
    public function empProfile() {
        return $this->hasOne(EmployeeProfile::class, 'user_id')->where('type','employee');
    }
}
