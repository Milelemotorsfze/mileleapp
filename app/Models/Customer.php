<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;
    public const CUSTOMER_TYPE_INDIVIDUAL = "Individual";
    public const CUSTOMER_TYPE_COMPANY = "Company";
    public const CUSTOMER_TYPE_GOVERMENT = "Governtment";
    public const CUSTOMER_TYPE_NGO = "NGO";
    public function country()
    {
        return $this->belongsTo(Country::class);
    }

}
