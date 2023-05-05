<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasFactory;
    protected $table = "suppliers";
    protected $fillable = [
        'supplier',
        'contact_person',
        'country_code',
        'contact_number',
        'country_code2',
        'alternative_contact_number',
        'email',
        'person_contact_by',
        'supplier_type',
        'created_by',
        'updated_by',
        'deleted_by'
    ];
    public function supplierAddons()
    {
        return $this->hasMany(SupplierAddons::class,'supplier_id','id');
    }
    public function paymentMethods()
    {
        return $this->hasMany(SupplierAvailablePayments::class,'supplier_id','id');
    }
}
