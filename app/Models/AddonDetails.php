<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AddonDetails extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = "addon_details";
    protected $fillable = [
        'addon_id',
        'addon_code',
        'addon_type_name',
        'payment_condition',
        'model_year_start',
        'model_year_end',
        'additional_remarks',
        'created_by',
        'updated_by',
        'deleted_by',
        'image',
        'description',
        'is_all_brands',
        'fixing_charges_included',
        'fixing_charge_amount',
        'status'
    ];
    public function AddonTypes()
    {
        return $this->hasMany(AddonTypes::class,'addon_details_id','id');
    }
    public function AddonTypesGroup()
    {
        return $this->hasMany(AddonTypes::class,'addon_details_id','id');
    }
    public function AddonName()
    {
        return $this->hasOne(Addon::class,'id','addon_id');
    }
    public function latestAddonType()
    {
        return $this->hasOne(AddonTypes::class,'addon_details_id','id')->latest();
    }
    public function AddonSuppliers()
    {
        return $this->hasMany(SupplierAddons::class,'addon_details_id','id');
    }
    public function PurchasePrices()
    {
        return $this->hasOne(SupplierAddons::class,'addon_details_id','id')->where('status','active')->latest('updated_at');
    }
    public function SellingPrice()
    {
        return $this->hasOne(AddonSellingPrice::class,'addon_details_id','id')->where('status','active')->latest('updated_at');
    }
    public function PendingSellingPrice()
    {
        return $this->hasOne(AddonSellingPrice::class,'addon_details_id','id')->where('status','pending')->latest('updated_at');
    }
    public function KitItems()
    {
        return $this->hasMany(KitCommonItem::class,'addon_details_id','id');
    }
    public function partNumbers()
    {
        return $this->hasMany(SparePartsNumber::class,'addon_details_id','id');
    }
}
