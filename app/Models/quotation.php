<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class quotation extends Model
{
    use HasFactory;
protected $fillable = ['calls_id', 'created_by', /* other fillable attributes */];
}
