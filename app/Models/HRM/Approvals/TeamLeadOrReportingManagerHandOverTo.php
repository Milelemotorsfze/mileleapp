<?php

namespace App\Models\HRM\Approvals;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\User;
class TeamLeadOrReportingManagerHandOverTo extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = "lead_or_manager_hand_over";
    protected $fillable = [
        'lead_or_manager_id',
        'approval_by_id',
        'status',
        'created_by',
        'updated_by',
        'deleted_by'
    ];
    public function handOverTo() {
        return $this->hasOne(User::class,'id','approval_by_id');
    }
}
