<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WOApprovalDataHistory extends Model
{
    use HasFactory;
    protected $table = "w_o_approval_data_histories";
    protected $fillable = ['w_o_approvals_id','wo_history_id'];
    // Define the relationship to WOApprovals
    public function approval()
    {
        return $this->belongsTo(WOApprovals::class, 'w_o_approvals_id');
    }

    // Define the relationship to WORecordHistory
    public function recordHistory()
    {
        return $this->belongsTo(WORecordHistory::class, 'wo_history_id');
    }
}
