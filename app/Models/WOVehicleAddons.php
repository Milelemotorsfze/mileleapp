<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WOVehicleAddons extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = "w_o_vehicle_addons";
    protected $fillable = [
        'w_o_vehicle_id',
        'addon_reference_id',
        'addon_reference_type',
        'addon_code',
        'addon_name',
        'addon_name_description',
        'addon_quantity',
        'addon_description',
        'created_by',
        'updated_by',
        'deleted_by',
        'delete_cvm_id',
    ];
    public function workOrderVehicle()
    {
        return $this->hasOne(WOVehicles::class,'id','w_o_vehicle_id');
    }
    public function vehicle() {
        return $this->belongsTo(WOVehicles::class,'w_o_vehicle_id','id');
    }
}
