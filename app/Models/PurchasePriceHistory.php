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
    public function SupplierAddon()
    {
        return $this->hasOne(SupplierAddons::class,'id','supplier_addon_id');
    }
    public function CreatedBy()
    {
        return $this->hasOne(User::class,'id','created_by');
    }
}
