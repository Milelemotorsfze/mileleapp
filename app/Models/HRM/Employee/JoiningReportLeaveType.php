<?php

namespace App\Models\HRM\Employee;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JoiningReportLeaveType extends Model
{
    use HasFactory;
    protected $table = "joining_report_leave_types";
    protected $fillable = [
        'joining_reports_id',
        'type_of_leave'
    ];
}
