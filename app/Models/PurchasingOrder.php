<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchasingOrder extends Model
{
    use HasFactory;
    protected $table = 'purchasing_order';
    public function purchasing_order_items()
    {
        return $this->hasMany(PurchasingOrderItems::class);
    }
    public function vehicles()
    {
        return $this->hasMany(Vehicles::class);
    }
}
