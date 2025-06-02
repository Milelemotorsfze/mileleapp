<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SoVariant extends Model
{
    use HasFactory, SoftDeletes;
    public function so_items()
    {
        return $this->hasMany(Soitems::class, 'so_variant_id','id');
    }
    public function variant()
    {
        return $this->belongsTo(Varaint::class, 'variant_id','id');
    }
}
