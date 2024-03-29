<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ModelSpecification extends Model
{
    use HasFactory;
    protected $table = 'model_specification';
    public function options()
    {
        return $this->hasMany(ModelSpecificationOption::class);
    }
}
