<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;
    protected $table = 'booking';
    protected $fillable = [
        'date',
        'booking_start_date',
        'booking_end_date',
        'vehicle_id',
        'calls_id',
        'created_by',
        'booking_requests_id',
    ];
    public function vehicle()
    {
        return $this->belongsTo(Vehicles::class,'vehicle_id','id');
    }
}
