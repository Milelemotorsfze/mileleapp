<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LOIItemPurchaseOrder extends Model
{
    use HasFactory;
    protected $table = "loi_item_purchase_orders";
    public function approvedLOI()
    {
        return $this->belongsTo(ApprovedLetterOfIndentItem::class, 'approved_loi_id');
    }

}
