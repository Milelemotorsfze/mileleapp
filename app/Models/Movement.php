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
    return $this->belongsTo(Warehouse::class, 'from', 'id');
}
public function toWarehouse()
{
    return $this->belongsTo(Warehouse::class, 'to', 'id');
}
public function Movementrefernce()
{
    return $this->belongsTo(MovementsReference::class, 'reference_id', 'id');
}
}
