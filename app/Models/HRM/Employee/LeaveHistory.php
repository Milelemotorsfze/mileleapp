<?php

namespace App\Models\HRM\Employee;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeaveHistory extends Model
{
    use HasFactory;
    protected $table = "leave_histories";
    protected $fillable = [
        'leave_id',
        'icon',
        'message',
    ];
}
