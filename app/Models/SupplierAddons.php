<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupplierAddons extends Model
{
    use HasFactory;
    protected $table = "supplier_addons";
    protected $fillable = [
        'supplier_id',
        'addon_details_id',
        'purchase_price_aed',
        'purchase_price_usd',
        'status',
        'created_by',
        'updated_by',
        'deleted_by'
    ];
    public function Suppliers()
    {
        return $this->hasOne(Supplier::class,'id','supplier_id');
    }
    public function supplierAddonDetails()
    {
        return $this->hasOne(AddonDetails::class,'id','addon_details_id');
    }
    public function updatedBy()
    {
        return $this->hasOne(User::class,'id','updated_by');
    }
    public function CreatedBy()
    {
        return $this->hasOne(User::class,'id','created_by');
    }
    public function Kit()
    {
        return $this->hasMany(KitItems::class,'supplier_addon_id','id');
    }
}
