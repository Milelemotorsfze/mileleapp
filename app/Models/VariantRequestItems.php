<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VariantRequestItems extends Model
{
    use HasFactory;
    protected $table = 'variant_request_items';
    protected $fillable = [
        'variant_request_id',
        'model_specification_id',
        'model_specification_options_id',
    ];
}
