<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehiclescarts extends Model
{
    use HasFactory;
protected $fillable = ['vehicle_id', 'created_by', 'quotation_id', /* other fillable attributes */];
}
