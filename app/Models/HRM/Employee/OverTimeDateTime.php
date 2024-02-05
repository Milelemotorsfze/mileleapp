<?php

namespace App\Models\HRM\Employee;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;


class OverTimeDateTime extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = "over_time_date_times";
    protected $fillable = ['over_times_id','start_datetime','end_datetime','remarks'];
    protected $appends = [
        'hours',
    ];
    public function getHoursAttribute() {
        $Hours = '';
        $t1 = Carbon::parse($this->start_datetime);
        $t2 = Carbon::parse($this->end_datetime);
        $minutes = $t2->diffInMinutes($t1);
        $h = floor($minutes/60);
        $m = $minutes - ($h*60);
        $Hours = $h.':'.$m;
        return $Hours;
    }
    public function overtime() {
        return $this->belongsTo(OverTime::class,'over_times_id','id');
    }
}
