<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Colorlog extends Model
{
    use HasFactory;

    protected $table = 'color_log';

    protected $fillable = [
        'time',
        'date',
        'status',
        'colorcode_id',
        'field',
        'old_value',
        'new_value',
        'created_by'
    ];
}
