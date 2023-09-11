<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookingRequest extends Model
{
    use HasFactory;
    protected $table = 'booking_requests';
    protected $fillable = [
        'date',
        'vehicle_id',
        'calls_id',
        'created_by',
        'status',
        'days',
        'process_by',
        'process_date',
        'reason',
    ];
}
