<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupplierAddonTemp extends Model
{
    use HasFactory;
    protected $table = "supplier_addon_temps";
    protected $fillable = [
        'addon_code',
        'currency',
        'purchase_price'
    ];
}
