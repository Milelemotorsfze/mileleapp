<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterModel extends Model
{
    use HasFactory;
    public function variant()
    {
        return $this->belongsTo(Varaint::class,'variant_id','id');
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
