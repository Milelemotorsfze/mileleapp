<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Masters\MasterOfficeLocation; 

class WOVehicleDeliveryStatus extends Model
{
    use HasFactory;
    protected $table = 'wo_veh_del_status';

    protected $fillable = [
        'w_o_vehicle_id',
        'user_id',
        'status',
        'comment',
        'location',
        'gdn_number',
        'delivery_at',
        'doc_delivery_date',
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
    public function locationName()
    {
        return $this->belongsTo(MasterOfficeLocation::class, 'location');
    }
}
