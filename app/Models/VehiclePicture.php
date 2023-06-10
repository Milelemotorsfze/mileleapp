<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VehiclePicture extends Model
{
    use HasFactory;
    public function vehicle()
    {
        return $this->belongsTo(Vehicles::class,'vehicle_id');
    }
}
