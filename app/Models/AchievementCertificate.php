<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AchievementCertificate extends Model
{
    use HasFactory;

    protected $fillable = [
        'achievement_name',
        'purpose_of_request',
        'employee_id',
        'name',
        'status',
        'comments',
    ];
}
