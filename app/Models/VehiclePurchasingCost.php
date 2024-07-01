<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VehiclePurchasingCost extends Model
{
    use HasFactory;
    protected $table = 'vehicle_purchasing_cost';
    protected $fillable = [
        'currency',
        'unit_price',
        'vehicles_id',
    ];
}
