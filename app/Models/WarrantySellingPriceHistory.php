<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WarrantySellingPriceHistory extends Model
{
    use HasFactory;
    public function updatedUser()
    {
        return $this->belongsTo(User::class,'updated_by');
    }
    public function createdUser()
    {
        return $this->belongsTo(User::class,'created_by');
    }
    public function statusUpdatedUser()
    {
        return $this->belongsTo(User::class,'status_updated_by');
    }
    public function warrantyBrand()
    {
        return $this->belongsTo(WarrantyBrands::class,'warranty_brand_id');
    }
}
