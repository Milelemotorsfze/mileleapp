<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AddonDescription extends Model
{
    use HasFactory;
    protected $table = "addon_descriptions";
    protected $fillable = [
        'addon_id',
        'description'
    ];
    public function AddonName()
    {
        return $this->hasOne(Addon::class,'id','addon_id');
    }
}
