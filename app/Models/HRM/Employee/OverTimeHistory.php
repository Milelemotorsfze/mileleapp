<?php

namespace App\Models\HRM\Employee;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class OverTimeHistory extends Model
{
    use HasFactory;
    protected $table = "over_time_histories";
    protected $fillable = ['over_times_id','icon','message'];
}
