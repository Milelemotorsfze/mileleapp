<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BOEPenalty extends Model
{
    use HasFactory;
    protected $table = "boe_penalties";
    protected $fillable = [
        'wo_boe_id',
        'invoice_date',
        'invoice_number',
        'penalty_amount',
        'payment_receipt',
        'remarks',
        'created_by',
        'updated_by',
    ];
    public function createdUser() {
        return $this->belongsTo(User::class,'created_by','id');
    }
    public function penaltyTypes()
    {
        return $this->hasMany(BOEPenaltyType::class,'boe_penalties_id','id');
    }
}