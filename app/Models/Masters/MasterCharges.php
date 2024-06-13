<?php

namespace App\Models\Masters;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterCharges extends Model
{
    use HasFactory;
    protected $table = "master_charges";
    protected $fillable = [
        'type',
        'addon_code',
        'name',
        'description',
        'created_by',
        'updated_by',
    ];
}
