<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Shipping extends Model
{
    use HasFactory,SoftDeletes;
    protected $table = 'shipping_charges';
    protected $fillable = [
        'price',
        'created_by',
        'created_at',
        'updated_at',
        'created_by',
        'to_port',
        'from_port',
        'shipping_medium_id',
        'suppliers_id',
        'updated_by',
        'cost_price',
    ];
    public function quotationItems()
    {
        return $this->morphMany('App\QuotationItem', 'reference');
    }
    public function shippingMedium()
    {
        return $this->belongsTo(ShippingMedium::class, 'shipping_medium_id');
    }
}
