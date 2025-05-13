<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SoVariant extends Model
{
    use HasFactory;
    public function so_items()
{
    return $this->hasMany(Soitems::class, 'so_variant_id','id');
}
}
