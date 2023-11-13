<?php

namespace App\Models\HRM\Approvals;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DepartmentHeadApprovals extends Model
{
    use HasFactory;
    protected $table = "department_head_approvals";
    protected $fillable = [
        'department_id',
        'department_head_id',
        'approval_by_id',
        'status',
        'created_by',
        'updated_by',
        'deleted_by'
    ];
}
