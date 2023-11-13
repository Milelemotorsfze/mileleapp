<?php

namespace App\Models\Masters;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterDivisionWithHead extends Model
{
    use HasFactory;
    protected $table = "master_division_with_heads";
    protected $fillable = [
        'name',
        'division_head_id',
        'number_of_year_of_experience',
        'created_by',
        'updated_by',
        'deleted_by'
    ];
}
