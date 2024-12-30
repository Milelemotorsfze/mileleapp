<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PFI extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = "pfi";
    public const PFI_STATUS_NEW = 'New';
    public const PFI_PAYMENT_STATUS_PAID = 'PAID';
    public const PFI_PAYMENT_STATUS_UNPAID = 'UNPAID';
    public const PFI_PAYMENT_STATUS_PARTIALY_PAID = 'PARTIALY PAID';
    public const PFI_PAYMENT_STATUS_CANCELLED = 'CANCELLED';
   
    public function supplier()
    {
        return $this->belongsTo(Supplier::class,'supplier_id','id');
    }
    public function pfiItems()
    {
        return $this->hasMany(PfiItem::class,'pfi_id','id');
    }
    public function customer()
    {
        return $this->belongsTo(Clients::class,'client_id','id');
    }
    public function country()
    {
        return $this->belongsTo(Country::class,'country_id','id');
    }
    public function createdBy()
    {
        return $this->belongsTo(User::class,'created_by','id');
    }
    public function updatedBy()
    {
        return $this->belongsTo(User::class,'updated_by','id');
    }
  
}
