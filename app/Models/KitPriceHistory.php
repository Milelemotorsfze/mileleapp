<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KitPriceHistory extends Model
{
    use HasFactory;
    protected $table = 'kit_purchase_price_histories';
    protected $fillable = [
        'addon_details_id',
        'old_price',
        'updated_price',
        'status',
        'created_by',
        'updated_by',
        'deleted_by'
    ];
}
