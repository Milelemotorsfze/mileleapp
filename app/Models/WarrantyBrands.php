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
    public $appends = [
        'policy_brands'
    ];
    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class,'created_by');
    }
    public function premium()
    {
        return $this->belongsTo(WarrantyPremiums::class,'warranty_premiums_id');
    }
    public function getPolicyBrandsAttribute()
    {
        $brands = Brand::whereHas('warrantyBrands', function ($query) {
            $query->where('selling_price',$this->selling_price)
                ->where('warranty_premiums_id',$this->warranty_premiums_id);
        })->get();

        return $brands;
    }
}
