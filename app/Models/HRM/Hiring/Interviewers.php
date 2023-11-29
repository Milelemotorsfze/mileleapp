<?php

namespace App\Models\HRM\Hiring;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Interviewers extends Model
{
    use HasFactory;
    protected $table = "interviewers";
    public $timestamps = false;
    protected $fillable = [
        'interview_summary_report_id',
        'interviewer_id',
        'round',
    ];
}
