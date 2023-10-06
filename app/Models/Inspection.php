<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inspection extends Model
{
    use HasFactory;
    protected $table = 'inspection';
    protected $fillable = [
        'id',
        'status',
        'vehicle_id',
        'remark',
        'stage',
        'detail',
        'processing_date',
        'process_remarks',
        'created_at',
        'updated_at',
        'created_by',
    ];
}
