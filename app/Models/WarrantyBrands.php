<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WarrantyBrands extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = "warranty_brands";
    protected $fillable = [
        'warranty_premiums_id',
        'brand_id',
        'price',
        'selling_price',
        'status',
        'created_by',
        'updated_by',
        'deleted_by'
    ];
    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }
}
