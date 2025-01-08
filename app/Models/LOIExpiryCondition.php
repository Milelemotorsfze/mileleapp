<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LOIExpiryCondition extends Model
{
    use HasFactory;
    public $table = 'loi_expiry_conditions';
    public const LOI_DURATION_TYPE_YEAR = "Year";
    public const LOI_DURATION_TYPE_MONTH = "Month";
    
    public function createdBy()
    {
        return $this->belongsTo(User::class,'created_by','id');
    }
    public function updatedBy()
    {
        return $this->belongsTo(User::class,'updated_by','id');
    }
}