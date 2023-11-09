<?php

namespace App\Models\Masters;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterExperienceLevel extends Model
{
    use HasFactory;
    protected $table = "master_experience_levels";
    protected $fillable = [
        'name',
        'number_of_year_of_experience',
        'created_by',
        'updated_by',
        'deleted_by'
    ];
}
