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
        'deleted_by',
        'status'
    ];
    protected $appends = [
        'is_deletable'
    ];
    public const SUPPLIER_TYPE_DEMAND_PLANNING = 'demand_planning';
    public function supplierAddons()
    {
        return $this->hasMany(SupplierAddons::class,'supplier_id','id');
    }
    public function supplierTypes()
    {
        return $this->hasMany(SupplierType::class);
    }
    public function paymentMethods()
    {
        return $this->hasMany(SupplierAvailablePayments::class,'supplier_id','id');
    }
    public function getIsDeletableAttribute()
    {
        $supplierInventories = SupplierInventory::where('supplier_id', $this->id)->count();
        if($supplierInventories <= 0) {
            $demands = Demand::where('supplier_id', $this->id)->count();
            if($demands <= 0) {
                $letterOfIndents = LetterOfIndent::where('supplier_id', $this->id)->count();
                if($letterOfIndents <= 0) {
                    return true;
                }
            }
        }
        return false;
    }
}
