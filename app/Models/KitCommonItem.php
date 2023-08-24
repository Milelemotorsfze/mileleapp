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
        'kit_item_vendors',
        'kit_item_total_purchase_price',

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
        $kitItem = KitCommonItem::find($this->id);

        $vendorMinPrice = $kitItem->least_price_vendor;
        $addonDetailId = $vendorMinPrice->addon_details_id;
        $partNumbers = SparePartsNumber::where('addon_details_id', $addonDetailId)->get();

        return $partNumbers;
    }
    public function getLeastPriceVendorAttribute() {
        $kitItem = KitCommonItem::find($this->id);

        $addonDetailIds = AddonDetails::where('description', $this->item_id)
            ->where('addon_id', $kitItem->item->addon_id)->where('addon_type_name','SP')->pluck('id');
        $kitModelNumbers = AddonTypes::where('addon_details_id', $this->addon_details_id)->pluck('model_number');
        $kitAddonDetails = AddonTypes::whereIn('addon_details_id', $addonDetailIds)->whereIn('model_number', $kitModelNumbers)
            ->pluck('addon_details_id');

       $vendorMinPrice = SupplierAddons::whereIn('addon_details_id', $kitAddonDetails)
           ->where('status', 'active')
           ->orderBy('purchase_price_aed','ASC')->first();

        return $vendorMinPrice;
    }
    public function getKitItemVendorsAttribute() {

        $kitItem = KitCommonItem::find($this->id);

        $addonDetailIds = AddonDetails::where('description', $this->item_id)
            ->where('addon_id', $kitItem->item->addon_id)->where('addon_type_name','SP')->pluck('id');
        $kitModelNumbers = AddonTypes::where('addon_details_id', $this->addon_details_id)->pluck('model_number');
        $kitAddonDetails = AddonTypes::whereIn('addon_details_id', $addonDetailIds)->whereIn('model_number', $kitModelNumbers)
                            ->pluck('addon_details_id');
        $kitItemVendors = SupplierAddons::whereIn('addon_details_id', $kitAddonDetails)
                            ->where('status', 'active')
                            ->get();

        return $kitItemVendors;
    }
    public function getKitItemTotalPurchasePriceAttribute() {
        $kitItem = KitCommonItem::find($this->id);

        $leastPriceVendor = $kitItem->least_price_vendor;
        $totalItemPrice = $kitItem->quantity * $leastPriceVendor->purchase_price_aed;
       return $totalItemPrice;
    }
}
