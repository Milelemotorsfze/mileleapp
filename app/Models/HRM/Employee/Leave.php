<?php

namespace App\Models\HRM\Employee;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Leave extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = "leaves";
    protected $fillable = [
        'employee_id',
        'type_of_leave',
        'type_of_leave_description',
        'leave_start_date',
        'leave_end_date',
        'total_no_of_days',
        'no_of_paid_days',
        'no_of_unpaid_days',
        'address_while_on_leave',
        'alternative_home_contact_no',
        'alternative_personal_email',
        'status',
        'action_by_employee',
        'employee_action_at',
        'comments_by_employee',
        'advance_or_loan_balance',
        'others',
        'action_by_hr_manager',
        'hr_manager_id',
        'hr_manager_action_at',
        'comments_by_hr_manager',
        'action_by_department_head',
        'department_head_id',
        'department_head_action_at',
        'comments_by_department_head',
        'to_be_replaced_by',
        'action_by_division_head',
        'division_head_id',
        'division_head_action_at',
        'comments_by_division_head',
        'created_by',
        'updated_by',
        'deleted_by'
        
    ];
}
