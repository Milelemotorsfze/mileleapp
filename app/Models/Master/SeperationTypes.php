<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SeperationTypes extends Model
{
    use HasFactory;
    protected $table = "seperation_types";
    protected $fillable = [
        'name',
    ];
}
