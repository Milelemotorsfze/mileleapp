<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VariantItems extends Model
{
    use HasFactory;
    protected $table = 'variant_items';
    public function model_specification()
    {
        return $this->belongsTo(ModelSpecification::class,'model_specification_id');
    }
    public function model_specification_option()
    {
        return $this->belongsTo(ModelSpecificationOption::class,'model_specification_options_id');
    }
}
