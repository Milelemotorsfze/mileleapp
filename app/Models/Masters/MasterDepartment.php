<?php

namespace App\Models\Masters;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Masters\MasterDivisionWithHead;

class MasterDepartment extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = "master_departments";
    protected $fillable = [
        'name',
        'division_id',
        'department_head_id',
        'approval_by_id',
        'status',
        'is_demand_planning',
        'created_by',
        'updated_by',
        'deleted_by'
    ];
    protected $appends = [
        'approval_by_name',
        'approval_by_email',
    ];
    public function getApprovalByNameAttribute() {
        $approvalByName = '';
        if($this->approval_by_id != null) {
            $approvalBy = User::find($this->approval_by_id);
            $approvalByName = $approvalBy->name;
        }
        return $approvalByName;
    }
    public function getApprovalByEmailAttribute() {
        $approvalByEmailName = '';
        if($this->approval_by_id != null) {
            $approvalByEmail = User::find($this->approval_by_id);
            $approvalByEmailName = $approvalByEmail->email;
        }
        return $approvalByEmailName;
    }
    public function notifications()
    {
        return $this->belongsToMany(DepartmentNotifications::class, 'dnaccess', 'master_departments_id', 'department_notifications_id');
    }
    public function division() {
        return $this->hasOne(MasterDivisionWithHead::class,'id','division_id');
    }
    public function departmentHead() {
        return $this->hasOne(User::class,'id','department_head_id');
    }
    public function departmentApprovalBy() {
        return $this->hasOne(User::class,'id','approval_by_id');
    }
}
