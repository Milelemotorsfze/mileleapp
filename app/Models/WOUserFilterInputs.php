<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WOUserFilterInputs extends Model
{
    use HasFactory;
    protected $table = "user_filters";
    protected $fillable = [
        'user_id',
        'filters',
    ];
}
