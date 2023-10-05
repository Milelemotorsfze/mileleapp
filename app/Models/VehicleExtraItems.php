<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VehicleExtraItems extends Model
{
    use HasFactory;
    protected $table = 'vehicles_extra_items';
    protected $fillable = [
        'item_name',
        'vehicle_id',
        'qty',
    ];
}
