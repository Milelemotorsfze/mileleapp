<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AddonTypes extends Model
{
    use HasFactory;
    protected $table = "addon_types";
    protected $fillable = [
        'addon_details_id',
        'brand_id',
        'model_id',
        'is_all_model_lines',
        'model_number',
        'created_by',
        'updated_by',
        'deleted_by'
    ];
    public function brands()
    {
        return $this->belongsTo(Brand::class,'brand_id','id');
    }
    public function modelLines()
    {
        return $this->belongsTo(MasterModelLines::class,'model_id','id');
    }
    public function modelDescription()
    {
        return $this->hasOne(MasterModelDescription::class,'id','model_number');
    }
    public function AddonModelGroup()
    {
        return $this->hasMany(AddonTypes::class,'brand_id','id');
    }
}
