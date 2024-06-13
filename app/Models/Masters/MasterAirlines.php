<?php

namespace App\Models\Masters;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterAirlines extends Model
{
    use HasFactory;
    protected $table = "master_airlines";
    protected $fillable = [
        'name',
        'code',
        'created_by',
    ];
}
