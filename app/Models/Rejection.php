<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rejection extends Model
{
    use HasFactory;
    protected $table = 'lead_rejection';
    protected $fillable = [
        'calls_id',
        'date',
        'Reason',
        'created_by',
        'sales_notes',
    ];
    public $timestamps = false;
}
