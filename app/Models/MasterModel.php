<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterModel extends Model
{
    use HasFactory;
    public function Variant()
    {
        return $this->hasOne(Varaint::class,'master_models_id');
    }
    public function supplierInventories()
    {
        return $this->hasMany(SupplierInventory::class);
    }
    public function createdBy()
    {
        return $this->belongsTo(User::class,'created_by','id');
    }
}
