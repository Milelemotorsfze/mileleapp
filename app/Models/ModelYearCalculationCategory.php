<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ModelYearCalculationCategory extends Model
{
    use HasFactory;
    public function modelYearRule()
    {
        return $this->belongsTo(ModelYearCalculationRule::class,'model_year_rule_id','id');
    }
}
