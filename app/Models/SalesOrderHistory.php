<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesOrderHistory extends Model
{
    use HasFactory;
    protected $fillable = ['type','so_id','user_id', 'created_at', 'updated_at'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
