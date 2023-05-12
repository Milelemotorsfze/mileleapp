<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hiring extends Model
{
    use HasFactory;
    protected $table = 'hirings';
    protected $fillable = [
        'job_title',
        'job_details',
        'job_role',
        'job_education',
        'job_experiance',
        'job_skills',
        'job_other'
    ];
    public $timestamps = false;
}
