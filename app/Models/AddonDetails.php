<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AddonDetails extends Model
{
    use HasFactory;
    protected $table = "addon_details";
    protected $fillable = [
        'addon_id',
        'addon_code',
        'addon_type_name',
        'part_number',
        'payment_condition',
        'lead_time',
        'additional_remarks',
        'created_by',
        'updated_by',
        'deleted_by',
        'image',
        'is_all_brands',
        'fixing_charges_included',
        'fixing_charge_amount',
        'status'
    ];
    public function AddonTypes()
    {
        return $this->hasMany(AddonTypes::class,'addon_details_id','id');
    }
    public function AddonName()
    {
        return $this->hasOne(Addon::class,'id','addon_id');
    }
    public function AddonSuppliers()
    {
        return $this->hasMany(SupplierAddons::class,'addon_details_id','id');
    }
    public function PurchasePrices()
    {
        return $this->hasOne(SupplierAddons::class,'addon_details_id','id')->where('status','active')->latest('updated_at');
    }
    public function LeastPurchasePrices()
    {
        return $this->hasOne(SupplierAddons::class,'addon_details_id','id')->where('status','active')->ofMany('purchase_price_aed', 'min');
    }
    // public function LeastPurchasePrices()
    // {
    //     return $this->hasOne(SupplierAddons::class,'addon_details_id','id');
    // }
    public function SellingPrice()
    {
        return $this->hasOne(AddonSellingPrice::class,'addon_details_id','id')->where('status','active')->latest('updated_at');
    }
}
