<?php

namespace App\Models\HRM\Employee;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Liability extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = "leaves";
    protected $fillable = [
        'employee_id',
        'request_date',
        'loan',
        'loan_amount',
        'advances',
        'advances_amount',
        'penalty_or_fine',
        'penalty_or_fine_amount',
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
}
