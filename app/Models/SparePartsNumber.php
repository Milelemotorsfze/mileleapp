<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SparePartsNumber extends Model
{
    use HasFactory;
    protected $table = "spare_parts_numbers";
    protected $fillable = [
        'addon_details_id',
        'part_number'
    ];
}
