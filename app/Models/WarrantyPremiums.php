<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WarrantyPremiums extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = "warranty_premiums";
    protected $fillable = [
        'warranty_policies_id',
        'supplier_id',
        'vehicle_category1',
        'vehicle_category2',
        'eligibility_year',
        'eligibility_milage',
        'extended_warranty_period',
        'is_open_milage',
        'extended_warranty_milage',
        'claim_limit_in_aed',
        'status',
        'created_by',
        'updated_by',
        'deleted_by',
    ];
    public function PolicyName()
    {
        return $this->hasOne(MasterWarrantyPolicies::class,'id','warranty_policies_id');
    }

    public function BrandPrice()
    {
        return $this->hasMany(WarrantyBrands::class,'warranty_premiums_id','id');
    }
    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }
}
