<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SupplierAvailablePayments extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = "supplier_available_payment_methods";
    protected $fillable = [
        'supplier_id',
        'payment_methods_id',
        'is_primary_payment_method',
        'created_by',
        'updated_by',
        'deleted_by'
    ];
    public function PaymentMethods()
    {
        return $this->hasOne(PaymentMethods::class,'id','payment_methods_id');
    }
}
