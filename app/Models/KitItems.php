<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KitItems extends Model
{
    use HasFactory;
    protected $table = 'kit_items';
    protected $fillable = [
        'addon_details_id',
        'supplier_addon_id',
        'quantity',
        'unit_price_in_aed',
        'total_price_in_aed',
        'unit_price_in_usd',
        'total_price_in_usd',
        'created_by',
        'updated_by',
        'deleted_by'
    ];
}
