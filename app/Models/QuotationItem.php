<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuotationItem extends Model
{
    use HasFactory;
    public function reference()
    {
        return $this->morphTo();
    }
    public $appends = [
        'quotation_addon_items',
//        'vehicle_unit_price',
    ];
    public function quotationSubItems() {

        return $this->hasMany(QuotationSubItem::class,'quotation_item_parent_id');
    }
    public function getQuotationAddonItemsAttribute() {
        $items = QuotationSubItem::with('quotationItem')
                ->whereHas('quotationItem', function ($query) {
                    $query->where('is_enable', true);
                })
                ->where('quotation_item_parent_id', $this->id)
                ->where('quotation_id', $this->quotation_id)
                ->get();

        return $items;
    }
    public function getVehicleUnitPriceAttribute() {
        $quantity = $this->quantity;
        $id = $this->id;
        $quotationId = $this->quotationId;
        $hidedItems = QuotationSubItem::with('quotationItem')
            ->whereHas('quotationItem', function ($query) {
                $query->where('is_enable', false);
            })
            ->where('quotation_item_parent_id', $this->id)
            ->where('quotation_id', $this->quotation_id)
            ->pluck('quotation_item_id')->toArray();

        if($hidedItems) {
            $addonTotalSum = QuotationItem::whereIn('id', $hidedItems)
                ->sum('total_amount');
            $amount = $addonTotalSum / $quantity;
            $unitAmount = $this->unit_price + $amount;
        }else{
            $unitAmount = $this->unit_price;
        }

        return $unitAmount;
    }

}
