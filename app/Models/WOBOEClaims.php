<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WOBOEClaims extends Model
{
    use HasFactory;
    protected $table = "wo_boe_claims";
    protected $fillable = [
        'wo_boe_id',
        'claim_date',
        'claim_reference_number',
        'status',
        'created_by',
        'updated_by',
    ];
    public function createdUser() {
        return $this->belongsTo(User::class,'created_by','id');
    }
}
