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
    public function appVehAgaDepo()
    {
        return $this->hasMany(WOApprovalDepositAganistVehicle::class,'w_o_approvals_id','id');
    }
    public function approvedAddons()
    {
        return $this->hasMany(WOApprovalAddonDataHistory::class,'w_o_approvals_id','id');
    }
    // Define the relationship to WOVehicleAddonRecordHistory through the pivot table WOApprovalAddonDataHistory
    public function vehicleAddonRecordHistories()
    {
        return $this->hasManyThrough(
            WOVehicleAddonRecordHistory::class,
            WOApprovalAddonDataHistory::class,
            'w_o_approvals_id', // Foreign key on WOApprovalAddonDataHistory table
            'id', // Foreign key on WOVehicleAddonRecordHistory table
            'id', // Local key on WOApprovals table
            'wo_addon_history_id' // Local key on WOApprovalAddonDataHistory table
        );
    }
    // Define the relationship to WOVehicleRecordHistory through the pivot table WOApprovalVehicleDataHistory
    public function vehicleRecordHistories()
    {
        return $this->hasManyThrough(
            WOVehicleRecordHistory::class,
            WOApprovalVehicleDataHistory::class,
            'w_o_approvals_id', // Foreign key on WOApprovalVehicleDataHistory table
            'id', // Foreign key on WOVehicleRecordHistory table
            'id', // Local key on WOApprovals table
            'wo_vehicle_history_id' // Local key on WOApprovalVehicleDataHistory table
        );
    }
}
