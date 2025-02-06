<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VariantRequest extends Model
{
    use HasFactory;
    protected $table = 'variant_request';
    protected $fillable = [
        'model_detail',
        'steering',
        'upholestry',
        'seat',
        'detail',
        'my',
        'gearbox',
        'fuel_type',
        'name',
        'engine',
        'status',
        'brands_id',
        'master_model_lines_id',
        'int_colour',
        'ex_colour',
    ];
}
