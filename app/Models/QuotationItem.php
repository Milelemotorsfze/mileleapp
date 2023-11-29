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
    public function quotationSubItems() {
        return $this->hasMany(QuotationSubItem::class,'quotation_item_parent_id');
    }
}
