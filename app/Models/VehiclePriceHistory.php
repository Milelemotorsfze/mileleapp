<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VehiclePriceHistory extends Model
{
    use HasFactory;
    public function availableColour()
    {
        return $this->belongsTo(AvailableColour::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class,'updated_by');
    }

}
