<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Logs extends Model
{
    use HasFactory;
    protected $table = 'logs';
    protected $fillable = [
        'table_name',
        'table_id',
        'action',
        'user_id',
    ];
    public $timestamps = false;
}
