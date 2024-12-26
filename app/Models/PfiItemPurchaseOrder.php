<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PfiItemPurchaseOrder extends Model
{
    use HasFactory;

    public function pfi()
    {
        return $this->belongsTo(PFI::class,'pfi_id');
    }
    public function purchaseOrder()
    {
        return $this->belongsTo(PurchasingOrder::class,'purchase_order_id','id');
    }
    
}
