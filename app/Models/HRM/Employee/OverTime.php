<?php

namespace App\Models\HRM\Employee;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\User;
use App\Models\HRM\Employee\OverTimeHistory;
use App\Models\HRM\Employee\OverTimeDateTime;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class OverTime extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = "over_times";
    protected $fillable = ['employee_id','status','action_by_employee','employee_action_at','comments_by_employee','action_by_department_head',
        'department_head_id','department_head_action_at','comments_by_department_head','action_by_division_head','division_head_id','division_head_action_at',
        'comments_by_division_head','action_by_hr_manager','hr_manager_id','hr_manager_action_at','comments_by_hr_manager','created_by','updated_by','deleted_by'
    ];
    protected $appends = [
        'total_hours',
        'current_status',
        'is_auth_user_can_approve',
    ];
    public function getTotalHoursAttribute() {
        $sumMinutes = 0;
        $sumTime = '';
        foreach($this->times as $time) {
            $explodedTime = explode(':', $time->hours); 
            $eachminutes = $explodedTime[0]*60+$explodedTime[1];
            $sumMinutes = $sumMinutes+$eachminutes;
        }
        $hours = floor($sumMinutes/60);
        $minutes = $sumMinutes - ($hours * 60);
        $sumTime = $hours.':'.$minutes;
        return $sumTime;
    }
    public function getCurrentStatusAttribute() {
        $currentStatus = '';
        if($this->status == 'approved') {
            $currentStatus = 'Approved';
        }
        else if($this->status == 'rejected') {
            $currentStatus = 'Rejected';
        }
        // Approvals =>  Employee -----------> Reporting Manager ---------> Division Head-------> HR Manager 
        else if($this->status == 'pending' && $this->action_by_employee == 'pending') {
            $currentStatus = "Employee's Approval Awaiting";
        }
        else if($this->status == 'pending' && $this->action_by_department_head == 'pending') {
            $currentStatus = "Reporting Manager's Approval Awaiting";
        }
        else if($this->status == 'pending' && $this->action_by_division_head == 'pending') {
            $currentStatus = "Division Head's Approval Awaiting";
        }      
        else if($this->status == 'pending' && $this->action_by_hr_manager == 'pending') {
            $currentStatus = "HR Manager's Approval Awaiting";
        }
        return $currentStatus;
    }
    public function getIsAuthUserCanApproveAttribute() {
        $isAuthUserCanApprove = [];
        $authId = Auth::id();
        // Approvals =>  Employee -----------> Reporting Manager ---------> Division Head-------> HR Manager 
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
        else if($this->action_by_employee =='approved' && $this->action_by_department_head == 'approved' && $this->action_by_division_head == 'pending' && 
            $this->division_head_id == $authId) {
                $isAuthUserCanApprove['can_approve'] = true;
                $isAuthUserCanApprove['current_approve_position'] = 'Division Head';
                $isAuthUserCanApprove['current_approve_person'] = $this->divisionHead->name;
        }
        else if($this->action_by_employee =='approved' && $this->action_by_department_head == 'approved' && $this->action_by_division_head == 'approved' && 
            $this->action_by_hr_manager == 'pending' && $this->hr_manager_id == $authId) {
                $isAuthUserCanApprove['can_approve'] = true;
                $isAuthUserCanApprove['current_approve_position'] = 'HR Manager';
                $isAuthUserCanApprove['current_approve_person'] = $this->hrManager->name;
        }
        return $isAuthUserCanApprove;
    }
    public function user() {
        return $this->hasOne(User::class,'id','employee_id');
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
    public function history() {
        return $this->hasMany(OverTimeHistory::class,'over_times_id','id');
    }
    public function times() {
        return $this->hasMany(OverTimeDateTime::class,'over_times_id','id');
    }
    public function minStartDateTime() {
        return $this->hasOne(OverTimeDateTime::class,'over_times_id','id')->ofMany('start_datetime','min');
    }
    public function maxStartDateTime() {
        return $this->hasOne(OverTimeDateTime::class,'over_times_id','id')->ofMany('end_datetime','max');
    }
}
