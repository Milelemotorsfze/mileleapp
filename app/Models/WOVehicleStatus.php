<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Masters\MasterOfficeLocation; 

class WOVehicleStatus extends Model
{
    use HasFactory;
    protected $table = 'wo_vehicle_status';

    protected $fillable = [
        'w_o_vehicle_id',
        'user_id',
        'status',
        'comment',
        'expected_completion_datetime',
        'current_vehicle_location',
        'vehicle_available_location',
    ];

    // Relationship with the WO Vehicles model
    public function woVehicle()
    {
        return $this->belongsTo(WoVehicle::class, 'w_o_vehicle_id');
    }

    // Relationship with the Users model
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relationship with the master office locations model
    public function location()
    {
        return $this->belongsTo(MasterOfficeLocation::class, 'vehicle_available_location');
    }
}
