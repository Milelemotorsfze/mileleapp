<?php

namespace App\Models\HRM\Hiring;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

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
    public function interviewerName() {
        return $this->hasOne(User::class,'id','interviewer_id');
    }
}
