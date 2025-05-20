<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Movement extends Model
{
    use HasFactory;
    public function vehicle()
    {
        return $this->belongsTo(Vehicles::class, 'vin', 'vin');
    }
    public function fromWarehouse()
    {
        return $this->belongsTo(Warehouse::class, 'from', 'id')->where('status', 1);
    }
    public function toWarehouse()
    {
        return $this->belongsTo(Warehouse::class, 'to', 'id')->where('status', 1);
    }
    public function Movementrefernce()
    {
        return $this->belongsTo(MovementsReference::class, 'reference_id', 'id');
    }
    public function MovementGrn()
    {
        return $this->belongsTo(MovementGrn::class, 'movement_grn_id', 'id');
    }
}
