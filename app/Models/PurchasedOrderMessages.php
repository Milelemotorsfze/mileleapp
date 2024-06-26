<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchasedOrderMessages extends Model
{
    use HasFactory;
    protected $fillable = ['purchasing_order_id', 'user_id', 'message'];
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function replies()
    {
        return $this->hasMany(PurchasedOrderReplies::class);
    }
}
