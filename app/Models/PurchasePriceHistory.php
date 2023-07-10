<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchasePriceHistory extends Model
{
    use HasFactory;
    protected $table = "purchase_price_histories";
    protected $fillable = [
        'supplier_addon_id',
        'purchase_price_aed',
        'purchase_price_usd',
        'status',
        'created_by',
        'updated_by',
        'deleted_by'
    ];
}
