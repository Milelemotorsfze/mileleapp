<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesPersonLaugauges extends Model
{
    use HasFactory;
    protected $table = 'sales_person_laugauges';
    protected $fillable = [
        'sales_person',
        'language',
    ];
}
