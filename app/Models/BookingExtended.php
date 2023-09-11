<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookingExtended extends Model
{
    use HasFactory;
    protected $table = 'booking_extended';
    protected $fillable = [
        'id',
        'reason',
        'days',
        'booking_id',
    ];
}
