<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterGrades extends Model
{
    use HasFactory;
    protected $table = 'master_vehicles_grades';
    protected $fillable = [
        'grade_name',
        'model_line_id',
        'created_by',
    ];
    public function modelLine()
    {
        return $this->belongsTo(MasterModelLines::class, 'model_line_id');
    }
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function modelDescriptions()
    {
        return $this->hasMany(MasterModelDescription::class, 'master_vehicles_grades_id');
    }
}
