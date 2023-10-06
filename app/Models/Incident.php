<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Incident extends Model
{
    use HasFactory;
    protected $table = 'incident';
    protected $fillable = [
        'inspection_id',
        'status',
        'reason',
        'file_path',
        'driven_by',
        'detail',
        'narration',
        'type',
        'vehicle_id',
        'created_by',
        'responsivity',
    ];
}
