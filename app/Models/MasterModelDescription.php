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
        'steering',
        'model_line_id',
        'master_vehicles_grades_id',
        'engine',
        'fuel_type',
        'transmission',
        'drive_train',
        'window_type',
        'model_description',
        'created_by',
    ];
    public function user()
    {
        return $this->belongsTo(User::class,'created_by','id');
    }
    public function modelLine()
    {
        return $this->belongsTo(MasterModelLines::class,'model_line_id','id');
    }
}
