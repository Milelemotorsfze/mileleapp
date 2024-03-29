<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MonthlyDemand extends Model
{
    use HasFactory;
    public function demandList()
    {
        return $this->belongsTo(DemandList::class);
    }
}
