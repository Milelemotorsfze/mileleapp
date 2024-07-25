<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WOApprovals extends Model
{
    use HasFactory;
    protected $table = "w_o_approvals";
    protected $fillable = ['work_order_id','type', 'status', 'comments','user_id','action_at'];
    public function user()
    {
        return $this->hasOne(User::class,'id','user_id');
    }
    public function workOrder()
    {
        return $this->hasOne(WorkOrder::class,'id','work_order_id');
    }
    public function recordHistories()
    {
        return $this->belongsToMany(WORecordHistory::class, 'w_o_approval_data_histories', 'w_o_approvals_id', 'wo_history_id');
    }
}
