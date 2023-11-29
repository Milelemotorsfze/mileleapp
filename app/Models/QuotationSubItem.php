<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuotationSubItem extends Model
{
    use HasFactory;
    public function quotationItem() {
        return $this->hasOne(QuotationItem::class,'id','quotation_item_id');
    }
}
