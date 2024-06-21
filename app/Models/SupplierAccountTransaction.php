<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupplierAccountTransaction extends Model
{
    use HasFactory;
    protected $table = "supplier_account_transaction";
    public function purchaseOrder()
    {
        return $this->belongsTo(PurchaseOrder::class, 'purchasing_order_id');
    }
}
