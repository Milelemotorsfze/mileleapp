<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Addon extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = "addons";
    protected $fillable = [
        'addon_type',
        'name',
        'created_by',
        'updated_by',
        'deleted_by'
    ];
}
