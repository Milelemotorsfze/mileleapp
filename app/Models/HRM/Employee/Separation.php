<?php

namespace App\Models\HRM\Employee;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Separation extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = "separations";
    protected $fillable = [
        'status','last_working_date','separation_type','seperation_type_other','replacement','replacement_other','jd_of_separation_employee','employee_id',
        'handover_tasks_description','action_by_employee','employee_action_at','comments_by_employee','takeover_employee_id','employment_type','department_head_id',
        'action_by_takeover_employee','takeover_employee_action_at','comments_by_takeover_employee','action_by_department_head','department_head_action_at',
        'comments_by_department_head','action_by_hr_manager','hr_manager_id','hr_manager_action_at','comments_by_hr_manager','jd_verified_at','tasks_verified_at',
        'sign_verified_at','created_by','updated_by','deleted_by'];
}
