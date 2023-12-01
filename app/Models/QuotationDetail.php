<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuotationDetail extends Model
{
    use HasFactory;
    public function country()
    {
        return $this->belongsTo(Country::class);
    }
    public function shippingPort()
    {
        return $this->belongsTo(MasterShippingPort::class);
    }
}
