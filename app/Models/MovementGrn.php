<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MovementGrn extends Model
{
    use HasFactory;

    public function Movementrefernce()
    {
        return $this->belongsTo(MovementsReference::class, 'movement_reference_id', 'id');
    }
}
