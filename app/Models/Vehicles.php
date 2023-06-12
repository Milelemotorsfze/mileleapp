<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehicles extends Model
{
    use HasFactory;
    protected $table = 'vehicles';
    public function variant()
    {
        return $this->belongsTo(Varaint::class,'varaints_id','id');
    }
}
