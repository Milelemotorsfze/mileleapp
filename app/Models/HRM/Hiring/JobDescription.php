<?php

namespace App\Models\HRM\Hiring;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Masters\MasterOfficeLocation;
use App\Models\Masters\MasterJobPosition;
use App\Models\Masters\MasterDepartment;
use App\Models\User;

class JobDescription extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = "job_descriptions";
    protected $fillable = [
        'hiring_request_id',
        'job_title',
        'department_id',
        'location_id',
        'reporting_to',
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
}
