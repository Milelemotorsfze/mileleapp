<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuotationItem extends Model
{
    use HasFactory;
    protected $appends = [
        'addon_sum',
    ];
    public function reference()
    {
        return $this->morphTo();
    }
    public function quotationSubItems() {
        return $this->hasMany(QuotationSubItem::class,'quotation_item_parent_id');
    }
    public function getAddonSumAttribute() {

        $quotationItem = QuotationItem::find($this->id);
        if($quotationItem->quotationSubItems) {
            $ids = QuotationSubItem::where('quotation_item_parent_id', $this->id)->pluck('quotation_item_id')->toArray();
            $sum = QuotationItem::whereIn('id', $ids)->sum('total_amount');
        }
       if($sum) {
           return $sum;
       }
       return 0.00;
    }
}
