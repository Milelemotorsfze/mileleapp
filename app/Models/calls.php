<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class calls extends Model
{
    use HasFactory;
    protected $table = 'calls';
    protected $fillable = [
        'name',
        'email',
        'sales_person',
        'remarks',
        'phone',
        'source',
        'status',
        'language',
        'location',
        'demand',
        'created_by',
        'created_at',
    ];
    public $timestamps = false;
}
