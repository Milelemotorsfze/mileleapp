<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ShippingCertification extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'shipping_certification';
    public function quotationItems()
    {
        return $this->morphMany('App\QuotationItem', 'reference');
    }
}
