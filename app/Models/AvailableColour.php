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
        return $this->belongsTo(Varaint::class,'varaint_id','id');
    }
    public function interior()
    {
        return $this->belongsTo(ColorCode::class,'int_colour','id');
    }
    public function exterior()
    {
        return $this->belongsTo(ColorCode::class,'ext_colour','id');
    }
    public function pictures()
    {
    return $this->hasMany(VariantPicture::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class,'updated_by','id');
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
