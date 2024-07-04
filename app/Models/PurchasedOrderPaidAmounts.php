<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchasedOrderPaidAmounts extends Model
{
    use HasFactory;
    protected $table = 'purchased_order_paid_amounts';
    public function purchasingOrder()
    {
        return $this->belongsTo(PurchasingOrder::class, 'purchasing_order_id');
    }
}
