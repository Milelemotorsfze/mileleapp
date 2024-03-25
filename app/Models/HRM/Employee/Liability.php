<?php

namespace App\Models\HRM\Employee;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class Liability extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = "liabilities";
    protected $fillable = [
        'employee_id',
        'request_date',
        // 'loan',
        // 'loan_amount',
        // 'advances',
        // 'advances_amount',
        // 'penalty_or_fine',
        // 'penalty_or_fine_amount',
        'type',
        'code',
        'total_amount',
        'no_of_installments',
        'amount_per_installment',
        'reason',
        'status',
        'action_by_employee',
        'employee_action_at',
        'comments_by_employee',
        'action_by_department_head',
        'department_head_id',
        'department_head_action_at',
        'comments_by_department_head',
        'action_by_finance_manager',
        'finance_manager_id',
        'finance_manager_action_at',
        'comments_by_finance_manager',
        'action_by_hr_manager',
        'hr_manager_id',
        'hr_manager_action_at',
        'comments_by_hr_manager',
        'action_by_division_head',
        'division_head_id',
        'division_head_action_at',
        'comments_by_division_head',
        'created_by',
        'updated_by',
        'deleted_by',
    ];
    protected $appends = [
        'liability_type',
        'is_auth_user_can_approve',
        'liability_code'
    ];
    public function getLiabilityTypeAttribute() {
        $liabilityType = '';
        if($this->type == 'loan') {
            $liabilityType = 'Loan';
        }
        else if($this->type == 'advances') {
            $liabilityType = 'Advances';
        }
        else if($this->type == 'penalty_or_fine') {
            $liabilityType = 'Penalty Or Fine';
        }
        return $liabilityType;
    }
    public function getIsAuthUserCanApproveAttribute() {
        $isAuthUserCanApprove = [];
        $authId = Auth::id();
         // employee -------> Reporting Manager  ----Finance Manager--------->HR Manager-------->Division Head
        if($this->action_by_employee =='pending' && $this->employee_id == $authId) {
            $isAuthUserCanApprove['can_approve'] = true;
            $isAuthUserCanApprove['current_approve_position'] = 'Employee';
            $isAuthUserCanApprove['current_approve_person'] = $this->user->name;
        }
        else if($this->action_by_employee =='approved' && $this->action_by_department_head == 'pending' && $this->department_head_id == $authId) { 
            $isAuthUserCanApprove['can_approve'] = true;
            $isAuthUserCanApprove['current_approve_position'] = 'Reporting Manager';
            $isAuthUserCanApprove['current_approve_person'] = $this->reportingManager->name;
        }
        else if($this->action_by_employee =='approved' && $this->action_by_department_head == 'approved' && $this->action_by_finance_manager == 'pending' && 
            $this->finance_manager_id == $authId) {
                $isAuthUserCanApprove['can_approve'] = true;
                $isAuthUserCanApprove['current_approve_position'] = 'Finance Manager';
                $isAuthUserCanApprove['current_approve_person'] = $this->divisionHead->name;
        }
        else if($this->action_by_employee =='approved' && $this->action_by_department_head == 'approved' && $this->action_by_finance_manager == 'approved' && 
            $this->action_by_hr_manager == 'pending' && $this->hr_manager_id == $authId) {
                $isAuthUserCanApprove['can_approve'] = true;
                $isAuthUserCanApprove['current_approve_position'] = 'HR Manager';
                $isAuthUserCanApprove['current_approve_person'] = $this->hrManager->name;
        }
        else if($this->action_by_employee =='approved' && $this->action_by_department_head == 'approved' && $this->action_by_finance_manager == 'approved' && 
            $this->action_by_hr_manager == 'approved' && $this->action_by_division_head == 'pending' && $this->division_head_id == $authId) {
                $isAuthUserCanApprove['can_approve'] = true;
                $isAuthUserCanApprove['current_approve_position'] = 'Division Head';
                $isAuthUserCanApprove['current_approve_person'] = $this->hrManager->name;
        }
        return $isAuthUserCanApprove;
    }
    public function getLiabilityCodeAttribute() {
        $liabilityCode = '';
        if($this->code != '' && $this->type != '' && $this->created_at != '') {
            $liabilityCode = 'ELF/';
            if($this->type == 'loan') {
                $liabilityCode =$liabilityCode.'LOAN/';
            }
            elseif($this->type == 'advances') {
                $liabilityCode =$liabilityCode.'ADVANCE/';
            }
            elseif($this->type == 'penalty_or_fine') {
                $liabilityCode =$liabilityCode.'FINE/';
            }
            $liabilityCode =$liabilityCode.\Carbon\Carbon::parse($this->created_at)->format('M_Y').'/'.$this->code;
        }
        return $liabilityCode;
    }
    public function user() {
        return $this->hasOne(User::class,'id','employee_id');
    }
    public function reportingManager() {
        return $this->hasOne(User::class,'id','department_head_id');
    }
    public function financeManager() {
        return $this->hasOne(User::class,'id','finance_manager_id');
    }
    public function hrManager() {
        return $this->hasOne(User::class,'id','hr_manager_id');
    }
    public function divisionHead() {
        return $this->hasOne(User::class,'id','division_head_id');
    }
    public function history() {
        return $this->hasMany(LiabilityHistory::class,'liability_id','id');
    }
}
