<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchasedOrderReplies extends Model
{
    use HasFactory;
    protected $fillable = ['purchased_order_messages_id', 'user_id', 'reply'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function message()
    {
        return $this->belongsTo(PurchasedOrderMessages::class);
    }
}
