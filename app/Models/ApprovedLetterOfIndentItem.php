<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApprovedLetterOfIndentItem extends Model
{
    use HasFactory;
    public function letterOfIndentItem()
    {
        return $this->belongsTo(LetterOfIndentItem::class,'letter_of_indent_item_id');
    }
    public function loiPurchaseOrder()
    {
        return $this->hasOne(LOIItemPurchaseOrder::class,'approved_loi_id','id');
    }
}
