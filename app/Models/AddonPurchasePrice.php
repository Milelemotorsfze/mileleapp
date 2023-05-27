<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AddonPurchasePrice extends Model
{
    use HasFactory;
    protected $table = "addon_purchase_prices";
    protected $fillable = [
        'addon_details_id',
        'purchase_price',
        'status',
        'created_by',
        'updated_by',
        'deleted_by'
    ];
}
