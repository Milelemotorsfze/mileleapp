<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WOApprovalVehicleDataHistory extends Model
{
    use HasFactory;
    protected $table = "wo_app_veh_histories";
    protected $fillable = ['w_o_approvals_id','wo_vehicle_history_id'];
}
