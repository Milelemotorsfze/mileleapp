<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PassportRelease extends Model
{
    use HasFactory;
    protected $table = "passport_releases";
    protected $fillable = [
        'employee_id',
        'passport_request_id',
        'purposes_of_release',
        'release_purpose',
        'release_submit_status',
        'release_action_by_employee',
        'release_employee_action_at',
        'release_comments_by_employee',
        'release_action_by_department_head',
        'release_department_head_id',
        'release_department_head_action_at',
        'release_comments_by_department_head',
        'release_action_by_division_head',
        'release_division_head_id',
        'release_division_head_action_at',
        'release_comments_by_division_head',
        'release_action_by_hr_manager',
        'release_hr_manager_id',
        'release_hr_manager_action_at',
        'release_comments_by_hr_manager',
        'created_by',
        'updated_by',
        'deleted_by'
    ];
}
