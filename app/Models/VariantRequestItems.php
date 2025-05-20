<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class VariantRequestItems extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'variant_request_items';
    protected $fillable = [
        'variant_request_id',
        'model_specification_id',
        'model_specification_options_id',
    ];
    protected $dates = ['deleted_at'];
}
