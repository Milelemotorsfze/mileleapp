<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuotationDetail extends Model
{
    use HasFactory;
    public function country()
    {
        return $this->hasOne(Country::class,'id','country_id');
    }
    public function shippingPort()
    {
        return $this->belongsTo(MasterShippingPorts::class);
    }
    public function shippingPortOfLoad()
    {
        return $this->belongsTo(MasterShippingPorts::class,'to_shipping_port_id','id');
    }
    public function paymentterms()
    {
        return $this->hasOne(PaymentTerms::class,'id','payment_terms');
    }
}
