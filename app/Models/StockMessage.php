<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockMessage extends Model
{
    use HasFactory;
    protected $table = 'stock_message';
    protected $fillable = ['vehicle_id', 'user_id', 'message'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function replies()
    {
        return $this->hasMany(StockReply::class);
    }
}
