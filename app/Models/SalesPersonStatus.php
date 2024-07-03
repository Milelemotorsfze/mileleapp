<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesPersonStatus extends Model
{
    use HasFactory;
    protected $table = 'sales_person_status'; 
    protected $fillable = [
        'sale_person_id',
        'status',
        'remarks',
        'created_by',
    ];
}
