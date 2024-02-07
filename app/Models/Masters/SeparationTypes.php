<?php

namespace App\Models\Masters;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SeparationTypes extends Model
{
    use HasFactory;
    protected $table = "seperation_types";
    protected $fillable = [
        'name',
    ];
}
