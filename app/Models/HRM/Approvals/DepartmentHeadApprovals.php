<?php

namespace App\Models\HRM\Approvals;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Masters\MasterDepartment;
use App\Models\User;
use Illuminate\Database\Eloquent\SoftDeletes;
class DepartmentHeadApprovals extends Model
{
    use HasFactory, SoftDeletes;
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
    protected $appends = [
        'department_name',
        'approval_by_name',
        'approval_by_email',
    ];
    public function getDepartmentNameAttribute() {
        $department = MasterDepartment::find($this->department_id);
        $departmentName = $department->name;
        return $departmentName;
    }
    public function getApprovalByNameAttribute() {
        $approvalBy = User::find($this->approval_by_id);
        $approvalByName = $approvalBy->name;
        return $approvalByName;
    }
    public function getApprovalByEmailAttribute() {
        $approvalByEmail = User::find($this->approval_by_id);
        $approvalByEmailName = $approvalByEmail->email;
        return $approvalByEmailName;
    }
}
