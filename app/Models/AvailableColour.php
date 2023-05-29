<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AvailableColour extends Model
{
    use HasFactory;
    protected $table = 'available_colour';
    protected $fillable = [
        'varaint_id',
        'int_colour',
        'ext_colour',
    ];
    public function variant()
    {
        return $this->belongsTo(Variant::class);
    }
    public function pictures()
    {
    return $this->hasMany(VariantPicture::class);
    }
    public function variantPicture()
    {
        return $this->hasMany(VariantPicture::class);
    }
    public function VariantsReel()
    {
        return $this->hasMany(VariantsReel::class);
    }
}
