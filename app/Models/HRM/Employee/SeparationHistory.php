<?php

namespace App\Models\HRM\Employee;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SeparationHistory extends Model
{
    use HasFactory;
    protected $table = "separation_histories";
    protected $fillable = [
        'separations_id',
        'icon',
        'message',
    ];
}
