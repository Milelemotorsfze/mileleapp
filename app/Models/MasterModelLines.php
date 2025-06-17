<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterModelLines extends Model
{
    use HasFactory;
    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }
    public function createdBy()
    {
        return $this->belongsTo(User::class,'created_by','id');
    }
    public function updatedBy()
    {
        return $this->belongsTo(User::class,'updated_by','id');
    }
    public function addons()
    {
        return $this->hasMany(AddonTypes::class,'model_id','id');
    }
    public function restricredOrAllowedModelLines() 
    {

        return $this->hasMany(LoiAllowedOrRestrictedModelLines::class,'model_line_id','id');
    }

    public function modelDescriptions()
    {
        return $this->hasMany(MasterModelDescription::class, 'model_line_id');
    }
}
