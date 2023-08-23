<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class KitCommonItem extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'kit_common_items';
    protected $fillable = [
        'addon_details_id',
        'item_id',
        'quantity',
        'created_by',
        'updated_by',
        'deleted_by'
    ];
    public $appends = [
        'addon_part_numbers',
        'least_price_vendor',
        'kit_item_vendors'
    ];
    public function addon()
    {
        return $this->hasOne(AddonDetails::class,'id','addon_details_id');
    }
    public function item()
    {
        return $this->hasOne(AddonDescription::class,'id','item_id');
    }
    public function partNumbers()
    {
        return $this->hasMany(SparePartsNumber::class,'addon_details_id','id');
    }
    public function getAddonPartNumbersAttribute() {

        $commonItems = KitCommonItem::where('addon_details_id', $this->addon_details_id)
            ->pluck('item_id');
        $addonDetailIds  = AddonDetails::whereIn('description', $commonItems)->pluck('id');
        $partNumbers = SparePartsNumber::whereIn('addon_details_id', $addonDetailIds)->get();

        return $partNumbers;
    }
    public function getLeastPriceVendorAttribute() {

       $addonDetailId = AddonDetails::where('description', $this->item_id)->where('addon_type_name','SP')->first();

       $vendorMinPrice = SupplierAddons::where('addon_details_id', $addonDetailId->id)
           ->where('status', 'active')
           ->orderBy('purchase_price_aed','ASC')->first();

       return $vendorMinPrice->supplier_id;
    }
    public function getKitItemVendorsAttribute() {

        $addonDetailId = AddonDetails::where('description', $this->item_id)->where('addon_type_name','SP')->first();

        $kitItemVendors = SupplierAddons::where('addon_details_id', $addonDetailId->id)
            ->where('status', 'active')
           ->get();

        return $kitItemVendors;
    }
}
