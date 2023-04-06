<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class calls extends Model
{
    use HasFactory;
    protected $table = 'calls';
    protected $fillable = [
        'date',
        'name',
        'email',
        'sales_person',
        'remarks',
        'phone',
        'sales_person',
        'user_id',
    ];
    public $timestamps = false;
}
