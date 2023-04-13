<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VariantPicture extends Model
{
    use HasFactory;
    protected $table = 'variants_pictures';
    public function availableColor()
    {
        return $this->belongsTo(AvailableColor::class);
    }
    }
