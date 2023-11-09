<?php

namespace App\Models\HRM\Hiring;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeHiringRequest extends Model
{
    use HasFactory;
    protected $table = "employee_hiring_requests";
    protected $fillable = [
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
        'action_by_hiring_manager',
        'hiring_manager_id',
        'hiring_manager_action_at',
        'comments_by_hiring_manager',
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
        'deleted_by'
    ];
}
