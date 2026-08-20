<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Per-VIN shipping details captured on the claim screen.
 *
 * Claim side only. These values are independent of the work order level
 * container_number / final_destination columns and never write to them.
 */
class WOBOEClaimShippingDetail extends Model
{
    use HasFactory;
    protected $table = "wo_boe_claim_shipping_details";
    protected $fillable = [
        'wo_boe_id',
        'w_o_vehicle_id',
        'wo_boe_claim_id',
        'container_number',
        'bl_number',
        'final_destination_country_id',
        'created_by',
        'updated_by',
    ];
    public function woBoe()
    {
        return $this->belongsTo(WOBOE::class,'wo_boe_id','id');
    }
    public function woVehicle()
    {
        return $this->belongsTo(WOVehicles::class,'w_o_vehicle_id','id');
    }
    public function claim()
    {
        return $this->belongsTo(WOBOEClaims::class,'wo_boe_claim_id','id');
    }
    public function finalDestinationCountry()
    {
        return $this->belongsTo(Country::class,'final_destination_country_id','id');
    }
    public function createdUser()
    {
        return $this->belongsTo(User::class,'created_by','id');
    }
    public function updatedUser()
    {
        return $this->belongsTo(User::class,'updated_by','id');
    }
}
