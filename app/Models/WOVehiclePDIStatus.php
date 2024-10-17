<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WOVehiclePDIStatus extends Model
{
    use HasFactory;
    protected $table = 'wo_vehicle_pdi_status';

    protected $fillable = [
        'w_o_vehicle_id',
        'user_id',
        'status',
        'comment',
        'pdi_scheduled_at',
        'passed_status',
        'qc_inspector_remarks',
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
}
