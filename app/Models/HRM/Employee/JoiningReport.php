<?php

namespace App\Models\HRM\Employee;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\User;
use App\Models\Masters\MasterOfficeLocation;
use Illuminate\Support\Facades\Auth;
use App\Models\Masters\MasterDepartment;

class JoiningReport extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = "joining_reports";
    protected $fillable = [
        'joining_type',
        'joining_date',
        'new_emp_joining_type',
        'joining_location',
        'transfer_from_department_id',
        'transfer_from_date',
        'transfer_from_location_id',
        'transfer_to_department_id',
        'remarks',
        'prepared_by_id',
        'action_by_prepared_by',
        'prepared_by_action_at',
        'comments_by_prepared_by',
        'employee_id',
        'action_by_employee',
        'employee_action_at',
        'comments_by_employee',
        'action_by_hr_manager',
        'hr_manager_id',
        'hr_manager_action_at',
        'comments_by_hr_manager',
        'action_by_department_head',
        'department_head_id',
        'department_head_action_at',
        'comments_by_department_head',
        'created_by',
        'updated_by',
        'deleted_by',
        'joining_reports_id',
    ];
    protected $appends = [
        'is_auth_user_can_approve',
        'joining_type_name',
    ];
    public function getIsAuthUserCanApproveAttribute() {
        $isAuthUserCanApprove = [];
        $authId = Auth::id();
        // Approvals =>  Prepared by -------> Employee -----------> HR Manager ---------> Reporting Manager
        if($this->action_by_prepared_by =='pending' && $this->prepared_by_id == $authId) {
            $isAuthUserCanApprove['can_approve'] = true;
            $isAuthUserCanApprove['current_approve_position'] = 'Prepared by';
            $isAuthUserCanApprove['current_approve_person'] = $this->preparedBy->name;
        }
        else if($this->action_by_prepared_by =='approved' && $this->action_by_employee == 'pending' && $this->employee_id == $authId) { 
            $isAuthUserCanApprove['can_approve'] = true;
            $isAuthUserCanApprove['current_approve_position'] = 'Employee';
            if($this->joining_type == 'new_employee') {
                $isAuthUserCanApprove['current_approve_person'] = $this->employee->first_name . ' ' . $this->employee->last_name;
            }
            else if($this->joining_type == 'internal_transfer' OR $this->joining_type == 'vacations_or_leave') {
                $isAuthUserCanApprove['current_approve_person'] = $this->user->name;
            }
        }
        else if($this->action_by_prepared_by =='approved' && $this->action_by_employee == 'approved' && $this->action_by_hr_manager == 'pending' && 
            $this->hr_manager_id == $authId) {
                $isAuthUserCanApprove['can_approve'] = true;
                $isAuthUserCanApprove['current_approve_position'] = 'HR Manager';
                $isAuthUserCanApprove['current_approve_person'] = $this->hr->name;
        }
        else if($this->action_by_prepared_by =='approved' && $this->action_by_employee == 'approved' && $this->action_by_hr_manager == 'approved' && 
            $this->action_by_department_head == 'pending' && $this->department_head_id == $authId) {
                $isAuthUserCanApprove['can_approve'] = true;
                $isAuthUserCanApprove['current_approve_position'] = 'Reporting Manager';
                $isAuthUserCanApprove['current_approve_person'] = $this->reportingManager->name;
        }
        return $isAuthUserCanApprove;
    }
    public function getJoiningTypeNameAttribute() {
        $joiningTypeName = '';
        if($this->joining_type == 'vacations_or_leave') {
            $joiningTypeName = 'Vacations Or Leave';
        }
        else if($this->joining_type == 'internal_transfer') {
            $joiningTypeName = 'Internal Transfer';
        }
        else if($this->joining_type == 'new_employee' && $this->new_emp_joining_type =='trial_period') {
            $joiningTypeName = 'New Employee - Trial Period';
        }
        else if($this->joining_type == 'new_employee' && $this->new_emp_joining_type =='permanent') {
            $joiningTypeName = 'New Employee - Permanent';
        }
        return $joiningTypeName;
    } 
    public function employee() {
        return $this->belongsTo(EmployeeProfile::class,'employee_id');
    }
    public function user() {
        return $this->belongsTo(User::class,'employee_id');
    }
    public function joiningLocation() {
        return $this->hasOne(MasterOfficeLocation::class,'id','joining_location');
    }
    public function transferFromLocation() {
        return $this->hasOne(MasterOfficeLocation::class,'id','transfer_from_location_id');
    }
    public function transferFromDepartment() {
        return $this->hasOne(MasterDepartment::class,'id','transfer_from_department_id');
    }
    public function transferToDepartment() {
        return $this->hasOne(MasterDepartment::class,'id','transfer_to_department_id');
    }
    public function reportingManager() {
        return $this->hasOne(User::class,'id','department_head_id');
    }
    public function preparedBy() {
        return $this->hasOne(User::class,'id','prepared_by_id');
    }
    public function history() {
        return $this->hasMany(JoiningReportHistory::class,'joining_report_id','id');
    }
    public function hr() {
        return $this->hasOne(User::class,'id','hr_manager_id');
    }
}
