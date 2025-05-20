<?php

namespace App\Models\Masters;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PenaltyTypes extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'penalty_types';

    protected $fillable = [
        'name',
        'is_active',
        'created_by',
        'updated_by',
        'deleted_by'
    ];
}
