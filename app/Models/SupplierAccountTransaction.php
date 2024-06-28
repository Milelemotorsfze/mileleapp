<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupplierAccountTransaction extends Model
{
    use HasFactory;
    protected $table = "supplier_account_transaction";
    protected $fillable = [
        'transaction_type',
        'purchasing_order_id',
        'supplier_account_id',
        'created_by',
        'account_currency',
        'transaction_amount',
    ];
    public function purchaseOrder()
    {
        return $this->belongsTo(PurchasingOrder::class, 'purchasing_order_id');
    }
}
