<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VehiclePenalty extends Model
{
    use HasFactory;
    protected $table = "vehicle_penalties";
    protected $fillable = [
        'payment_date',
        'wo_vehicle_id',
        'excess_days',
        'total_penalty_amount',
        'amount_paid',
        'payment_receipt',
        'remarks',
        'created_by',
        'updated_by',
    ];
    public function createdUser() {
        return $this->belongsTo(User::class,'created_by','id');
    }
}