<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoiRestrictedCountry extends Model
{
    use HasFactory;
    const STATUS_ACTIVE = 'Active';
    const STATUS_INACTIVE = 'Inactive';
    public function country()
    {
        return $this->belongsTo(Country::class,'country_id','id');
    }
    public function updatedBy()
    {
        return $this->belongsTo(User::class,'updated_by','id');
    }
}
