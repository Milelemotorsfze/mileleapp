<?php

namespace App\Models\HRM\Employee;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JoiningReportHistory extends Model
{
    use HasFactory;
    protected $table = "joining_report_histories";
    protected $fillable = [
        'joining_report_id',
        'icon',
        'message',
    ];
}
