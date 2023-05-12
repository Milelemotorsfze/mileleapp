<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VariantsReel extends Model
{
    use HasFactory;
    protected $table = 'variants_reels';
protected $fillable = ['available_colour_id', 'reel_path', 'created_by', 'video_path',/* other fillable attributes */];
}
function variantsReels()
{
    return $this->hasMany(VariantsReel::class);
}
