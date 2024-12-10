<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WOVehicleClaims extends Model
{
    use HasFactory;
    protected $table = "w_o_vehicle_claims";
    protected $fillable = [
        'wo_vehicle_id',
        'claim_date',
        'claim_reference_number',
        'status',
        'created_by',
        'updated_by',
    ];
    public function createdUser() {
        return $this->belongsTo(User::class,'created_by','id');
    }
}
