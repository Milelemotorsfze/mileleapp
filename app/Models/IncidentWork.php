<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IncidentWork extends Model
{
    use HasFactory;
    protected $table = 'incident_works';
    protected $fillable = [
        'works',
        'status',
        'remarks',
        'incident_id',
    ];
}
