<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesTargets extends Model
{
    use HasFactory;
    protected $table = 'sales_targets';
    // Inside SalesTargets model
public function sales_targets_lead_time()
{
    return $this->hasMany(SalesTargetsLeadTime::class, 'sales_targets_id');
}
}
