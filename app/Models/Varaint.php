<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Varaint extends Model
{
    use HasFactory;
    protected $table = 'varaints';
    public function availableColors()
    {
        return $this->hasMany(AvailableColour::class, 'varaint_id');
    }
    public function masterModel()
    {
        return $this->belongsTo(MasterModel::class,'master_models_id');
    }
    public function master_model_lines()
    {
        return $this->belongsTo(MasterModelLines::class,'master_model_lines_id');
    }
    public function brand()
    {
        return $this->belongsTo(Brand::class,'brands_id');
    }
}
