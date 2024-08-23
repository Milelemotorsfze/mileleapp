<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockReply extends Model
{
    use HasFactory;
    protected $table = 'stock_reply';
    protected $fillable = ['message_id', 'user_id', 'reply'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function message()
    {
        return $this->belongsTo(StockMessage::class);
    }
}
