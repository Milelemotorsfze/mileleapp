<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShippingRate extends Model
{
    use HasFactory;
    protected $table = 'shipping_rates';
    protected $fillable = [
        'cost_price',
        'selling_price',
        'status',
        'shipping_charges_id',
        'created_by',
        'suppliers_id',
    ];
}
