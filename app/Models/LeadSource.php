<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeadSource extends Model
{
    use HasFactory;
    protected $table = 'lead_source';
    protected $fillable = [
        'source_name',
        'status',
        'created_by',
    ];
    public $timestamps = false;
}
