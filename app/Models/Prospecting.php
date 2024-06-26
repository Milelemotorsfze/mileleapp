<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prospecting extends Model
{
    use HasFactory;
    public function call()
    {
        return $this->belongsTo(Calls::class, 'calls_id');
    }
}
