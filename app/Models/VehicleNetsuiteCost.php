<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VehicleNetsuiteCost extends Model
{
    use HasFactory;
    protected $table = 'vehicle_netsuite_cost';
    protected $fillable = [
        'vehicles_id',
        'cost',
        'netsuite_link',
        'created_by',
    ];
}
