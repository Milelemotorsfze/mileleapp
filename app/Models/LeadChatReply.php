<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeadChatReply extends Model
{
    protected $fillable = ['chat_id', 'user_id', 'reply'];
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function message()
    {
        return $this->belongsTo(PurchasedOrderMessages::class);
    }
}   
