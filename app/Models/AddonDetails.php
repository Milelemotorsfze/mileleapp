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
        'purchase_price',
        'selling_price',
        'payment_condition',
        'currency',
        'lead_time',
        'additional_remarks',
        'created_by',
        'updated_by',
        'deleted_by',
        'image',
        'is_all_brands'
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
}
