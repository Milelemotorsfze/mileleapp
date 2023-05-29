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
        'contact_number',
        'alternative_contact_number',
        'email',
        'person_contact_by',
        'supplier_type',
        'created_by',
        'updated_by',
        'deleted_by'
    ];
    public const SUPPLIER_TYPE_DEMAND_PLANNING = 'demand_planning';
    public function supplierAddons()
    {
        return $this->hasMany(SupplierAddons::class,'supplier_id','id');
    }
    public function paymentMethods()
    {
        return $this->hasMany(SupplierAvailablePayments::class,'supplier_id','id');
    }
    public function supplierTypes()
    {
        return $this->hasMany(SupplierType::class,'supplier_id','id');
    }
}
