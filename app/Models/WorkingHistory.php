<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkingHistory extends Model
{
    use HasFactory;
    protected $table = 'working_history';
    protected $fillable = [
        'company_name',
        'designation',
        'location',
        'todate',
        'fromdate',
        'emp_profile_id',
    ];
    public $timestamps = false;
}
