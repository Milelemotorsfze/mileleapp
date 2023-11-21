<?php

namespace App\Models\HRM\Hiring;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobDescription extends Model
{
    use HasFactory;
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
}
