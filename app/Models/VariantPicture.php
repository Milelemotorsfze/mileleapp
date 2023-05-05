<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VariantPicture extends Model
{
    use HasFactory;
    protected $table = 'variants_pictures';
    protected $fillable = ['available_colour_id', 'image_path', 'status', /* other fillable attributes */];
    public function availableColor()
    {
        return $this->belongsTo(AvailableColor::class);
    }
    }
