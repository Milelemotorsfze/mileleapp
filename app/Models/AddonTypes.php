<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AddonTypes extends Model
{
    use HasFactory;
    protected $table = "addon_types";
    protected $fillable = [
        'addon_details_id',
        'brand_id',
        'model_id',
        'created_by',
        'updated_by',
        'deleted_by'
    ];
}
