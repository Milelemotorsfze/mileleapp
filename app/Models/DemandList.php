<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DemandList extends Model
{
    use HasFactory;
    public function monthlyDemands()
    {
        return $this->hasMany(MonthlyDemand::class);
    }
}
