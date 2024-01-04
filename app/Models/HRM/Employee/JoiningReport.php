<?php

namespace App\Models\HRM\Employee;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\User;
use App\Models\Masters\MasterOfficeLocation;

class JoiningReport extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = "joining_reports";
    protected $fillable = [
        'joining_type',
        'trial_period_joining_date',
        'permanent_joining_date',
        'permanent_joining_location_id',
        'transfer_from_department_id',
        'transfer_from_date',
        'transfer_from_location_id',
        'transfer_to_department_id',
        'transfer_to_date',
        'transfer_to_location_id',
        'leave_joining_date',
        'location_id',
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
    ];
    public function employee() {
        return $this->belongsTo(EmployeeProfile::class,'employee_id');
    }
    public function location() {
        return $this->hasOne(MasterOfficeLocation::class,'id','location_id');
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
}
