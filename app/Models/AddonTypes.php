<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AddonTypes extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = "addon_types";
    protected $fillable = [
        'addon_details_id',
        'brand_id',
        'model_id',
        'is_all_model_lines',
        'model_number',
        'model_year_start',
        'model_year_end',
        'created_by',
        'updated_by',
        'deleted_by'
    ];
    protected $appends = [
        'kit_model_numbers',
        'model_numbers'
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
    public function getKitModelNumbersAttribute() {
        $addonType = AddonTypes::find($this->id);
        $modelDescriptions = AddonTypes::where('brand_id', $addonType->brand_id)
            ->where('addon_details_id', $addonType->addon_details_id)
            ->where('model_id', $addonType->model_id)
            ->pluck('model_number')->toArray();

        return $modelDescriptions;
    }
    public function getModelNumbersAttribute() {
        $addonType = AddonTypes::find($this->id);
        $modelDescriptions = MasterModelDescription::where('model_line_id', $addonType->model_id)
            ->get();
        return $modelDescriptions;
    }
}
