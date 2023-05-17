<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StrategiesDates extends Model
{
    use HasFactory;
    protected $table = 'strategies_dates';
    protected $fillable = [
        'cost',
        'starting_date',
        'ending_date',
        'strategies_id',
    ];
    public $timestamps = false;
}
