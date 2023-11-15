<?php

namespace App\Models\Masters;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterRecuritmentSource extends Model
{
    use HasFactory;
    protected $table = "masters_recuritment_sources";
    protected $fillable = [
        'name',
        'status',
        'created_by',
        'updated_by',
        'deleted_by'
    ];
}
