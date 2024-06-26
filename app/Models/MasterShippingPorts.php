<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterShippingPorts extends Model
{
    use HasFactory;
    protected $table = "master_shipping_ports";
    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id');
    }
}
