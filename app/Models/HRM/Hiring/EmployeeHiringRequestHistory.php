<?php

namespace App\Models\HRM\Hiring;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeHiringRequestHistory extends Model
{
    use HasFactory;
    protected $table = "employee_hiring_request_histories";
    protected $fillable = [
        'hiring_request_id',
        'icon',
        'message',
    ];
}
