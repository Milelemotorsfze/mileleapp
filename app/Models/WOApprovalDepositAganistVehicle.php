<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WOApprovalDepositAganistVehicle extends Model
{
    use HasFactory;
    protected $table = "w_o_app_dep_aga_veh";
    protected $fillable = ['w_o_approvals_id','w_o_vehicle_id'];
    public function vehicle()
    {
        return $this->hasOne(WOVehicles::class,'id','w_o_vehicle_id');
    }
}
