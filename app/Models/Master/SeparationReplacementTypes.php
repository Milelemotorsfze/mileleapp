<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SeparationReplacementTypes extends Model
{
    use HasFactory;
    protected $table = "separation_replacement_types";
    protected $fillable = [
        'name',
    ];
}
