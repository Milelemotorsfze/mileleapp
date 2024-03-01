<?php

namespace App\Models\HRM\Approvals;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Masters\MasterJobPosition;
use App\Models\User;
use Illuminate\Database\Eloquent\SoftDeletes;
class ApprovalByPositions extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = "approval_by_positions";
    protected $fillable = [
        'approved_by_position',
        'approved_by_id',
        'handover_to_id',
        'status',
        'created_by',
        'updated_by',
        'deleted_by'
    ];
    protected $appends = [
        'approved_by_position_name',
        'handover_to_name',
        'handover_to_email'
    ];
    public function getApprovedByPositionNameAttribute() {
        $approvedByPositionName = $this->approved_by_position;
        return $approvedByPositionName;
    }
    public function getHandoverToNameAttribute() {
        $handoverTo = User::find($this->handover_to_id);
        $handoverToName = $handoverTo->name;
        return $handoverToName;
    }
    public function gethandoverToEmailAttribute() {
        $handoverToEmail = User::find($this->handover_to_id);
        $handoverToEmailName = $handoverToEmail->email;
        return $handoverToEmailName;
    }
    public function designationPerson() {
        return $this->hasOne(User::class,'id','approved_by_id');
    }
}
