<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MasterModelDescription extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = "master_model_descriptions";
    protected $fillable = [
        'model_line_id',
        'model_description'
    ];
}
