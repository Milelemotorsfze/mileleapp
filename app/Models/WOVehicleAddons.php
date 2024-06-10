<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WOVehicleAddons extends Model
{
    use HasFactory;
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
    ];
}
