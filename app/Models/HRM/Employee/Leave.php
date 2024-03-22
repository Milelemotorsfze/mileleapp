<?php

namespace App\Models\HRM\Employee;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class Leave extends Model {
    use HasFactory, SoftDeletes;
    protected $table = "leaves";
    protected $fillable = [
        'employee_id','type_of_leave','type_of_leave_description','leave_start_date','leave_end_date','total_no_of_days','no_of_paid_days','no_of_unpaid_days',
        'address_while_on_leave','alternative_home_contact_no','alternative_personal_email','status','action_by_employee','employee_action_at','comments_by_employee',
        'advance_or_loan_balance','others','action_by_hr_manager','hr_manager_id','hr_manager_action_at','comments_by_hr_manager','action_by_department_head',
        'department_head_id','department_head_action_at','comments_by_department_head','to_be_replaced_by','action_by_division_head','division_head_id',
        'division_head_action_at','comments_by_division_head','created_by','updated_by','deleted_by','joining_reports_id'      
    ];
    protected $appends = [
        'leave_type',
        'is_auth_user_can_approve',
    ];
    public function getLeaveTypeAttribute() {
        $leaveType = '';
        if($this->type_of_leave == 'annual') {
            $leaveType = 'Annual';
        }
        else if($this->type_of_leave == 'sick') {
            $leaveType = 'Sick';
        }
        else if($this->type_of_leave == 'unpaid') {
            $leaveType = 'Unpaid';
        }
        else if($this->type_of_leave == 'maternity_or_peternity') {
            $leaveType = 'Maternity Or Peternity';
        }
        else if($this->type_of_leave == 'others') {
            $leaveType = 'Others';
        }
        return $leaveType;
    }
    public function getIsAuthUserCanApproveAttribute() {
        $isAuthUserCanApprove = [];
        $authId = Auth::id();
         // employee -------> HR Manager-------->Reporting Manager----->Division Head
        if($this->action_by_employee =='pending' && $this->employee_id == $authId) {
            $isAuthUserCanApprove['can_approve'] = true;
            $isAuthUserCanApprove['current_approve_position'] = 'Employee';
            $isAuthUserCanApprove['current_approve_person'] = $this->user->name;
        }
        else if($this->action_by_employee =='approved' && $this->action_by_hr_manager == 'pending' && $this->hr_manager_id == $authId) { 
            $isAuthUserCanApprove['can_approve'] = true;
            $isAuthUserCanApprove['current_approve_position'] = 'HR Manager';
            $isAuthUserCanApprove['current_approve_person'] = $this->hrManager->name;
        }
        else if($this->action_by_employee =='approved' && $this->action_by_hr_manager == 'approved' && $this->action_by_department_head == 'pending' && 
            $this->department_head_id == $authId) {
                $isAuthUserCanApprove['can_approve'] = true;
                $isAuthUserCanApprove['current_approve_position'] = 'Reporting Manager';
                $isAuthUserCanApprove['current_approve_person'] = $this->reportingManager->name;
        }
        else if($this->action_by_employee =='approved' && $this->action_by_hr_manager == 'approved' && $this->action_by_department_head == 'approved' && 
            $this->action_by_division_head == 'pending' && $this->division_head_id == $authId) {
                $isAuthUserCanApprove['can_approve'] = true;
                $isAuthUserCanApprove['current_approve_position'] = 'Division Head';
                $isAuthUserCanApprove['current_approve_person'] = $this->divisionHead->name;
        }
        return $isAuthUserCanApprove;
    }
    public function user() {
        return $this->hasOne(User::class,'id','employee_id');
    }
    public function history() {
        return $this->hasMany(LeaveHistory::class,'leave_id','id');
    }
    public function reportingManager() {
        return $this->hasOne(User::class,'id','department_head_id');
    }
    public function hrManager() {
        return $this->hasOne(User::class,'id','hr_manager_id');
    }
    public function divisionHead() {
        return $this->hasOne(User::class,'id','division_head_id');
    }
    public function toBeReplacedBy() {
        return $this->hasOne(User::class,'id','to_be_replaced_by');
    }
}
