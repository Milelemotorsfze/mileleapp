<?php

namespace App\Models\HRM\Employee;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PassportRequest extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = "passport_requests";
    protected $fillable = [
        'employee_id',
        'passport_status',
        'purposes_of_submit',
        'submit_status',
        'submit_action_by_employee',
        'submit_employee_action_at',
        'submit_comments_by_employee',
        'submit_action_by_department_head',
        'submit_department_head_id',
        'submit_department_head_action_at',
        'submit_comments_by_department_head',
        'submit_action_by_division_head',
        'submit_division_head_id',
        'submit_division_head_action_at',
        'submit_comments_by_division_head',
        'submit_action_by_hr_manager',
        'submit_hr_manager_id',
        'submit_hr_manager_action_at',
        'submit_comments_by_hr_manager',
        // 'purposes_of_release',
        // 'release_purpose',
        // 'release_submit_status',
        // 'release_action_by_employee',
        // 'release_employee_action_at',
        // 'release_comments_by_employee',
        // 'release_action_by_department_head',
        // 'release_department_head_id',
        // 'release_department_head_action_at',
        // 'release_comments_by_department_head',
        // 'release_action_by_division_head',
        // 'release_division_head_id',
        // 'release_division_head_action_at',
        // 'release_comments_by_division_head',
        // 'release_action_by_hr_manager',
        // 'release_hr_manager_id',
        // 'release_hr_manager_action_at',
        // 'release_comments_by_hr_manager',
        'created_by',
        'updated_by',
        'deleted_by'
    ];
}
