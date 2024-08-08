<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WOApprovalAddonDataHistory extends Model
{
    use HasFactory;
    protected $table = "w_o_approval_addon_data_histories";
    protected $fillable = ['w_o_approvals_id','wo_addon_history_id'];
    // Define the inverse relationship to WOApprovals
    public function woApproval()
    {
        return $this->belongsTo(WOApprovals::class, 'w_o_approvals_id');
    }

    // Define the relationship to WOVehicleAddonRecordHistory
    public function vehicleAddonRecordHistory()
    {
        return $this->belongsTo(WOVehicleAddonRecordHistory::class, 'wo_addon_history_id');
    }
}
