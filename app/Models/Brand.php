<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    use HasFactory;
    protected $table = "brands";
    public function varaint()
    {
        return $this->hasOne(Varaint::class);
    }

    public function masterModelLines()
    {
        return $this->belongsTo(MasterModelLines::class);
    }
    public function createdBy()
    {
        return $this->belongsTo(User::class,'created_by','id');
    }
    public function updatedBy()
    {
        return $this->belongsTo(User::class,'updated_by','id');
    }
    public function warrantyBrands()
    {
        return $this->hasMany(WarrantyBrands::class);
    }
}
